<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Enums\OrderStatus;
use App\Mail\MonthlyOrderReport;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * #98928 – Feature tests for the report:monthly-orders Artisan command.
 *
 * Covers:
 *   - Correct mailable sent to configured admin with valid --month
 *   - Mailable carries correct report data (totals, top products)
 *   - Default month (previous calendar month) when --month is omitted
 *   - Invalid --month returns failure and sends no mail
 *   - Missing/blank admin recipient returns failure and sends no mail
 *   - Empty reporting month still sends report with zero values
 *   - Mailable rendering: subject, month, top products, VND formatting
 */
class SendMonthlyOrderReportTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        config(['services.admin_notification.email' => 'admin@brewbite.test']);
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * Create an Order with a given status and forcefully set its updated_at.
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

        Order::withoutTimestamps(function () use ($order, $updatedAt): void {
            Order::where('id', $order->id)->update([
                'created_at' => $updatedAt,
                'updated_at' => $updatedAt,
            ]);
        });

        return $order->fresh();
    }

    /**
     * Attach order items to an order.
     *
     * @param  array<int, array{product_id: int, product_name: string, quantity: int, unit_price: float}>  $items
     */
    private function addItems(Order $order, array $items): void
    {
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['quantity'] * $item['unit_price'],
                'product_variant_id' => $item['product_variant_id'] ?? null,
            ]);
        }
    }

    /**
     * Create a minimal Product (no image/category required for report tests).
     */
    private function makeProduct(string $name): Product
    {
        return Product::factory()->create(['name' => $name]);
    }

    // ── Test 1: Sends one MonthlyOrderReport to configured admin ──────────────

    public function test_command_sends_one_mailable_to_configured_admin(): void
    {
        Mail::fake();

        $order = $this->createOrder(OrderStatus::COMPLETED, 150_000, '2026-08-10 10:00:00');
        $product = $this->makeProduct('Espresso');
        $this->addItems($order, [
            ['product_id' => $product->id, 'product_name' => 'Espresso', 'quantity' => 3, 'unit_price' => 50_000],
        ]);

        $this->artisan('report:monthly-orders', ['--month' => '2026-08'])
            ->assertSuccessful();

        Mail::assertSent(MonthlyOrderReport::class, 1);
        Mail::assertSent(
            MonthlyOrderReport::class,
            fn (MonthlyOrderReport $mail) => $mail->hasTo('admin@brewbite.test'),
        );
    }

    // ── Test 2: Mailable carries correct report data ──────────────────────────

    public function test_command_passes_correct_report_data_to_mailable(): void
    {
        Mail::fake();

        $product = $this->makeProduct('Latte');
        $order = $this->createOrder(OrderStatus::COMPLETED, 200_000, '2026-08-15 09:00:00');
        $this->addItems($order, [
            ['product_id' => $product->id, 'product_name' => 'Latte', 'quantity' => 4, 'unit_price' => 50_000],
        ]);

        $this->artisan('report:monthly-orders', ['--month' => '2026-08'])
            ->assertSuccessful();

        Mail::assertSent(
            MonthlyOrderReport::class,
            function (MonthlyOrderReport $mail): bool {
                $report = $mail->report;

                return $report['month'] === '2026-08'
                    && $report['total_orders'] === 1
                    && $report['total_revenue'] === '200000.00'
                    && $report['total_products_sold'] === 4
                    && count($report['top_products']) === 1
                    && $report['top_products'][0]['product_name'] === 'Latte'
                    && $report['top_products'][0]['quantity_sold'] === 4;
            },
        );
    }

    // ── Test 3: Default month is the previous calendar month ──────────────────

    public function test_default_month_is_previous_calendar_month(): void
    {
        Mail::fake();

        Carbon::setTestNow('2026-09-01 00:00:00');

        try {
            $order = $this->createOrder(
                OrderStatus::COMPLETED,
                100_000,
                '2026-08-20 10:00:00',
            );

            $product = $this->makeProduct('Americano');

            $this->addItems($order, [
                [
                    'product_id' => $product->id,
                    'product_name' => 'Americano',
                    'quantity' => 2,
                    'unit_price' => 50_000,
                ],
            ]);

            $this->artisan('report:monthly-orders')
                ->assertSuccessful();

            Mail::assertSent(
                MonthlyOrderReport::class,
                fn (MonthlyOrderReport $mail) => $mail->report['month'] === '2026-08',
            );
        } finally {
            Carbon::setTestNow();
        }
    }

    // ── Test 4: Invalid --month returns failure, no mail sent ─────────────────

    public function test_invalid_month_format_returns_failure(): void
    {
        Mail::fake();

        $this->artisan('report:monthly-orders', ['--month' => 'invalid'])
            ->assertFailed();

        Mail::assertNothingSent();
    }

    public function test_partial_date_month_format_returns_failure(): void
    {
        Mail::fake();

        $this->artisan('report:monthly-orders', ['--month' => '2026-8'])
            ->assertFailed();

        Mail::assertNothingSent();
    }

    public function test_out_of_range_month_returns_failure(): void
    {
        Mail::fake();

        $this->artisan('report:monthly-orders', ['--month' => '2026-13'])
            ->assertFailed();

        Mail::assertNothingSent();
    }

    public function test_full_date_string_returns_failure(): void
    {
        Mail::fake();

        $this->artisan('report:monthly-orders', ['--month' => '2026-08-01'])
            ->assertFailed();

        Mail::assertNothingSent();
    }

    public function test_zero_year_month_format_returns_failure(): void
    {
        Mail::fake();

        $this->artisan('report:monthly-orders', ['--month' => '0000-01'])
            ->assertFailed();

        Mail::assertNothingSent();
    }

    // ── Test 5: Missing/blank admin recipient returns failure ─────────────────

    public function test_missing_admin_email_returns_failure(): void
    {
        Mail::fake();

        config(['services.admin_notification.email' => null]);

        $this->artisan('report:monthly-orders', ['--month' => '2026-08'])
            ->assertFailed();

        Mail::assertNothingSent();
    }

    public function test_blank_admin_email_returns_failure(): void
    {
        Mail::fake();

        config(['services.admin_notification.email' => '   ']);

        $this->artisan('report:monthly-orders', ['--month' => '2026-08'])
            ->assertFailed();

        Mail::assertNothingSent();
    }

    // ── Test 6: Empty month still sends report with zero values ───────────────

    public function test_empty_month_sends_report_with_zero_values(): void
    {
        Mail::fake();

        // No completed orders in 2026-08.
        $this->artisan('report:monthly-orders', ['--month' => '2026-08'])
            ->assertSuccessful();

        Mail::assertSent(
            MonthlyOrderReport::class,
            function (MonthlyOrderReport $mail): bool {
                $report = $mail->report;

                return $report['month'] === '2026-08'
                    && $report['total_orders'] === 0
                    && $report['total_revenue'] === '0.00'
                    && $report['average_order_value'] === '0.00'
                    && $report['total_products_sold'] === 0
                    && $report['top_products'] === [];
            },
        );
    }

    // ── Test 7: Mailable rendering ────────────────────────────────────────────

    public function test_mailable_has_correct_subject(): void
    {
        $report = [
            'month' => '2026-08',
            'total_orders' => 0,
            'total_revenue' => '0.00',
            'average_order_value' => '0.00',
            'total_products_sold' => 0,
            'top_products' => [],
        ];

        $mailable = new MonthlyOrderReport($report);

        $mailable->assertHasSubject('Brew & Bite - Monthly Order Report 2026-08');
    }

    public function test_mailable_renders_month_and_zero_stats(): void
    {
        $report = [
            'month' => '2026-08',
            'total_orders' => 0,
            'total_revenue' => '0.00',
            'average_order_value' => '0.00',
            'total_products_sold' => 0,
            'top_products' => [],
        ];

        $mailable = new MonthlyOrderReport($report);
        $rendered = $mailable->render();

        $this->assertStringContainsString('2026-08', $rendered);
        $this->assertStringContainsString('No completed orders were recorded for this month', $rendered);
    }

    public function test_mailable_renders_top_products_and_vnd_format(): void
    {
        $product = $this->makeProduct('Cappuccino');
        $order = $this->createOrder(OrderStatus::COMPLETED, 300_000, '2026-08-12 10:00:00');
        $this->addItems($order, [
            ['product_id' => $product->id, 'product_name' => 'Cappuccino', 'quantity' => 6, 'unit_price' => 50_000],
        ]);

        $report = [
            'month' => '2026-08',
            'total_orders' => 1,
            'total_revenue' => '300000.00',
            'average_order_value' => '300000.00',
            'total_products_sold' => 6,
            'top_products' => [
                ['product_id' => $product->id, 'product_name' => 'Cappuccino', 'quantity_sold' => 6],
            ],
        ];

        $mailable = new MonthlyOrderReport($report);
        $rendered = $mailable->render();

        $this->assertStringContainsString('2026-08', $rendered);
        $this->assertStringContainsString('Cappuccino', $rendered);
        $this->assertStringContainsString('6', $rendered);
        // VND formatting: 300,000 VND
        $this->assertStringContainsString('300,000', $rendered);
        $this->assertStringContainsString('VND', $rendered);
    }
}
