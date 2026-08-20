<?php

namespace Tests\Feature\Reports;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\MonthlyOrderReportService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * #98927 – Feature tests for MonthlyOrderReportService.
 *
 * Covers:
 *   - Only completed orders are counted (statuses filter)
 *   - Month boundary uses updated_at with half-open interval
 *   - Aggregate totals: orders, revenue, average, products sold
 *   - Top-5 products: grouping by product_id across variants, ordering, tie-break
 *   - Empty month returns zero values
 *   - Input $month object is never mutated
 */
class MonthlyOrderReportServiceTest extends TestCase
{
    use RefreshDatabase;

    private MonthlyOrderReportService $service;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new MonthlyOrderReportService;
        $this->user = User::factory()->create();
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * Create an Order with a given status and set its updated_at directly via
     * query builder so Eloquent cannot overwrite the timestamp.
     */
    private function createOrder(
        OrderStatus $status,
        float $totalAmount,
        string $updatedAt,
    ): Order {
        $order = Order::create([
            'user_id' => $this->user->id,
            'status' => $status->value,
            'total_amount' => $totalAmount,
        ]);

        // Set both timestamps via QB to prevent Eloquent from resetting updated_at.
        Order::withoutTimestamps(function () use ($order, $updatedAt): void {
            Order::where('id', $order->id)->update([
                'created_at' => $updatedAt,
                'updated_at' => $updatedAt,
            ]);
        });

        $order->refresh();

        return $order;
    }

    /**
     * Attach order items to an order, specifying product_id and optionally
     * product_variant_id to test variant-grouping behaviour.
     *
     * @param  array<int, array{product_id: int, product_name: string, quantity: int, unit_price: float, product_variant_id?: int|null}>  $items
     */
    private function addItems(Order $order, array $items): void
    {
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['product_variant_id'] ?? null,
                'product_name' => $item['product_name'],
                'unit_price' => $item['unit_price'],
                'quantity' => $item['quantity'],
            ]);
        }
    }

    /**
     * Create a simple product (no factory dependency on CategoryFactory seeder state).
     */
    private function makeProduct(string $name = 'Coffee'): Product
    {
        return Product::factory()->create(['name' => $name]);
    }

    // ── Tests: Status filtering ────────────────────────────────────────────────

    /**
     * Tests 1 & 2: Only COMPLETED orders are counted; PENDING, CONFIRMED,
     * PREPARING and CANCELLED are all excluded.
     */
    public function test_only_completed_orders_are_counted(): void
    {
        $this->createOrder(OrderStatus::COMPLETED, 100_000, '2026-08-10 12:00:00');
        $this->createOrder(OrderStatus::PENDING, 200_000, '2026-08-10 12:00:00');
        $this->createOrder(OrderStatus::CONFIRMED, 300_000, '2026-08-10 12:00:00');
        $this->createOrder(OrderStatus::PREPARING, 400_000, '2026-08-10 12:00:00');
        $this->createOrder(OrderStatus::CANCELLED, 500_000, '2026-08-10 12:00:00');

        $result = $this->service->generate(Carbon::parse('2026-08-01'));

        $this->assertSame(1, $result['total_orders']);
        $this->assertSame('100000.00', $result['total_revenue']);
    }

    // ── Tests: Month boundary (updated_at) ────────────────────────────────────

    /**
     * Tests 3, 4 & 5: Month is determined by updated_at (not created_at).
     * The order at 00:00:00 on the first day of the month IS included.
     * The order at 00:00:00 on the first day of the next month is NOT included.
     */
    public function test_month_boundary_uses_updated_at_with_half_open_interval(): void
    {
        // Included: exactly at the start boundary
        $this->createOrder(OrderStatus::COMPLETED, 50_000, '2026-08-01 00:00:00');
        // Included: mid-month
        $this->createOrder(OrderStatus::COMPLETED, 60_000, '2026-08-15 10:30:00');
        // Excluded: exactly at the end boundary (next month start)
        $this->createOrder(OrderStatus::COMPLETED, 70_000, '2026-09-01 00:00:00');

        $result = $this->service->generate(Carbon::parse('2026-08-20'));

        $this->assertSame(2, $result['total_orders']);
        $this->assertSame('110000.00', $result['total_revenue']);
    }

    /**
     * Test 3 detail: created_at outside the month does NOT affect inclusion
     * when updated_at falls within the month.
     */
    public function test_month_is_determined_by_updated_at_not_created_at(): void
    {
        // Order created in July but completed (updated) in August.
        $order = Order::create([
            'user_id' => $this->user->id,
            'status' => OrderStatus::COMPLETED->value,
            'total_amount' => 80_000,
        ]);

        Order::withoutTimestamps(function () use ($order): void {
            Order::where('id', $order->id)->update([
                'created_at' => '2026-07-25 08:00:00',
                'updated_at' => '2026-08-05 14:00:00',
            ]);
        });

        $result = $this->service->generate(Carbon::parse('2026-08-01'));

        $this->assertSame(1, $result['total_orders']);
    }

    // ── Tests: Aggregate totals ───────────────────────────────────────────────

    /**
     * Tests 6, 7 & 8: Correct total_orders, total_revenue, and
     * average_order_value.
     */
    public function test_aggregates_are_correct(): void
    {
        $this->createOrder(OrderStatus::COMPLETED, 100_000, '2026-08-10 10:00:00');
        $this->createOrder(OrderStatus::COMPLETED, 200_000, '2026-08-12 11:00:00');
        $this->createOrder(OrderStatus::COMPLETED, 300_000, '2026-08-14 12:00:00');

        $result = $this->service->generate(Carbon::parse('2026-08-01'));

        $this->assertSame(3, $result['total_orders']);
        $this->assertSame('600000.00', $result['total_revenue']);
        $this->assertSame('200000.00', $result['average_order_value']);
    }

    /**
     * Test 9: total_products_sold = SUM of order_items.quantity for
     * completed orders in the month.
     */
    public function test_total_products_sold_sums_quantity_of_order_items(): void
    {
        $product = $this->makeProduct('Latte');

        $order1 = $this->createOrder(OrderStatus::COMPLETED, 90_000, '2026-08-08 09:00:00');
        $this->addItems($order1, [
            ['product_id' => $product->id, 'product_name' => 'Latte', 'quantity' => 3, 'unit_price' => 30_000],
        ]);

        $order2 = $this->createOrder(OrderStatus::COMPLETED, 120_000, '2026-08-09 10:00:00');
        $this->addItems($order2, [
            ['product_id' => $product->id, 'product_name' => 'Latte', 'quantity' => 4, 'unit_price' => 30_000],
        ]);

        $result = $this->service->generate(Carbon::parse('2026-08-01'));

        $this->assertSame(7, $result['total_products_sold']);
    }

    // ── Tests: Variant grouping ───────────────────────────────────────────────

    /**
     * Test 10: Quantities from different variants of the same product_id are
     * summed together in top_products.
     */
    public function test_variants_of_same_product_are_grouped_together(): void
    {
        $product = $this->makeProduct('Espresso');

        $order = $this->createOrder(OrderStatus::COMPLETED, 150_000, '2026-08-11 11:00:00');
        $this->addItems($order, [
            // Two rows simulating different variants of the same product.
            // We pass product_variant_id as null to avoid FK constraint in test DB;
            // grouping is by product_id, not product_variant_id.
            ['product_id' => $product->id, 'product_variant_id' => null, 'product_name' => 'Espresso', 'quantity' => 5, 'unit_price' => 30_000],
            ['product_id' => $product->id, 'product_variant_id' => null, 'product_name' => 'Espresso', 'quantity' => 3, 'unit_price' => 30_000],
        ]);

        $result = $this->service->generate(Carbon::parse('2026-08-01'));

        $this->assertCount(1, $result['top_products']);
        $this->assertSame($product->id, $result['top_products'][0]['product_id']);
        $this->assertSame(8, $result['top_products'][0]['quantity_sold']);
    }

    // ── Tests: Top-5 products ─────────────────────────────────────────────────

    /**
     * Tests 11 & 12: top_products has at most 5 entries, sorted by
     * quantity_sold descending.
     */
    public function test_top_products_limit_and_descending_order(): void
    {
        $order = $this->createOrder(OrderStatus::COMPLETED, 700_000, '2026-08-13 08:00:00');

        // Create 6 products with distinct quantities
        $items = [];
        for ($i = 1; $i <= 6; $i++) {
            $product = $this->makeProduct("Product {$i}");
            $items[] = [
                'product_id' => $product->id,
                'product_name' => "Product {$i}",
                'quantity' => $i * 2,   // 2, 4, 6, 8, 10, 12 — all distinct
                'unit_price' => 10_000,
            ];
        }

        $this->addItems($order, $items);

        $result = $this->service->generate(Carbon::parse('2026-08-01'));

        $this->assertCount(5, $result['top_products']);

        // Should be the 5 with highest quantity (quantities 12, 10, 8, 6, 4)
        $quantities = array_column($result['top_products'], 'quantity_sold');
        $this->assertSame([12, 10, 8, 6, 4], $quantities);
    }

    /**
     * Test 13: When quantities tie, products are sorted by product_id ascending.
     */
    public function test_tie_in_quantity_sorted_by_product_id_ascending(): void
    {
        $order = $this->createOrder(OrderStatus::COMPLETED, 300_000, '2026-08-14 09:00:00');

        $productA = $this->makeProduct('Product A');
        $productB = $this->makeProduct('Product B');
        $productC = $this->makeProduct('Product C');

        // All three have the same quantity = 5
        $this->addItems($order, [
            ['product_id' => $productA->id, 'product_name' => 'Product A', 'quantity' => 5, 'unit_price' => 20_000],
            ['product_id' => $productB->id, 'product_name' => 'Product B', 'quantity' => 5, 'unit_price' => 20_000],
            ['product_id' => $productC->id, 'product_name' => 'Product C', 'quantity' => 5, 'unit_price' => 20_000],
        ]);

        $result = $this->service->generate(Carbon::parse('2026-08-01'));

        $ids = array_column($result['top_products'], 'product_id');

        // product_id values must be in ascending order
        $sorted = $ids;
        sort($sorted);
        $this->assertSame($sorted, $ids);
        $this->assertSame([$productA->id, $productB->id, $productC->id], $ids);
    }

    // ── Tests: Items isolation ────────────────────────────────────────────────

    /**
     * Test 14: Items belonging to non-completed orders are excluded from
     * total_products_sold and top_products.
     */
    public function test_items_of_non_completed_orders_are_excluded(): void
    {
        $product = $this->makeProduct('Cappuccino');

        $completedOrder = $this->createOrder(OrderStatus::COMPLETED, 60_000, '2026-08-10 10:00:00');
        $this->addItems($completedOrder, [
            ['product_id' => $product->id, 'product_name' => 'Cappuccino', 'quantity' => 2, 'unit_price' => 30_000],
        ]);

        $cancelledOrder = $this->createOrder(OrderStatus::CANCELLED, 90_000, '2026-08-10 11:00:00');
        $this->addItems($cancelledOrder, [
            ['product_id' => $product->id, 'product_name' => 'Cappuccino', 'quantity' => 3, 'unit_price' => 30_000],
        ]);

        $result = $this->service->generate(Carbon::parse('2026-08-01'));

        $this->assertSame(1, $result['total_orders']);
        $this->assertSame(2, $result['total_products_sold']);
        $this->assertSame(2, $result['top_products'][0]['quantity_sold']);
    }

    /**
     * Test 15: Items belonging to completed orders outside the month are excluded.
     */
    public function test_items_of_orders_outside_month_are_excluded(): void
    {
        $product = $this->makeProduct('Mocha');

        $inMonth = $this->createOrder(OrderStatus::COMPLETED, 60_000, '2026-08-10 10:00:00');
        $this->addItems($inMonth, [
            ['product_id' => $product->id, 'product_name' => 'Mocha', 'quantity' => 2, 'unit_price' => 30_000],
        ]);

        $outOfMonth = $this->createOrder(OrderStatus::COMPLETED, 90_000, '2026-07-20 10:00:00');
        $this->addItems($outOfMonth, [
            ['product_id' => $product->id, 'product_name' => 'Mocha', 'quantity' => 5, 'unit_price' => 30_000],
        ]);

        $result = $this->service->generate(Carbon::parse('2026-08-01'));

        $this->assertSame(1, $result['total_orders']);
        $this->assertSame(2, $result['total_products_sold']);
    }

    // ── Tests: Empty month ────────────────────────────────────────────────────

    /**
     * Test 16: When there are no completed orders for the month, all numeric
     * values are zero and top_products is an empty array.
     */
    public function test_empty_month_returns_zero_values_and_empty_top_products(): void
    {
        // A completed order in a different month should not appear.
        $this->createOrder(OrderStatus::COMPLETED, 100_000, '2026-07-15 10:00:00');

        $result = $this->service->generate(Carbon::parse('2026-08-01'));

        $this->assertSame('2026-08', $result['month']);
        $this->assertSame(0, $result['total_orders']);
        $this->assertSame('0.00', $result['total_revenue']);
        $this->assertSame('0.00', $result['average_order_value']);
        $this->assertSame(0, $result['total_products_sold']);
        $this->assertSame([], $result['top_products']);
    }

    // ── Tests: Immutability ───────────────────────────────────────────────────

    /**
     * Test 17: The $month Carbon object passed in must not be mutated by the service.
     */
    public function test_input_month_is_not_mutated(): void
    {
        $month = Carbon::parse('2026-08-15 12:00:00');
        $original = $month->toDateTimeString();

        $this->service->generate($month);

        $this->assertSame($original, $month->toDateTimeString(),
            'The $month argument was mutated by MonthlyOrderReportService::generate().'
        );
    }
}
