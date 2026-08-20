<?php

namespace Tests\Feature\Order;

use App\Enums\OrderStatus;
use App\Enums\ProductType;
use App\Events\OrderCreated;
use App\Listeners\SendAdminOrderEmail;
use App\Mail\AdminOrderNotification;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminOrderEmailTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['name' => 'Test Customer']);

        $category = Category::create([
            'name' => 'Coffee',
            'slug' => 'coffee',
        ]);

        $currentProduct = Product::create([
            'category_id' => $category->id,
            'name' => 'Current Product Name',
            'slug' => 'current-product-name',
            'type' => ProductType::DRINK,
            'price' => 35000,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);

        $this->order = Order::create([
            'user_id' => $this->user->id,
            'status' => OrderStatus::PENDING,
            'total_amount' => 60000,
        ]);

        OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $currentProduct->id,
            'product_name' => 'Black Coffee Snapshot',
            'unit_price' => 30000,
            'quantity' => 2,
        ]);
    }

    public function test_listener_sends_one_email_to_configured_admin(): void
    {
        Mail::fake();

        config(['services.admin_notification.email' => 'admin@example.test']);

        $listener = new SendAdminOrderEmail;
        $listener->handle(new OrderCreated($this->order));

        Mail::assertSent(AdminOrderNotification::class, 1);

        Mail::assertSent(
            AdminOrderNotification::class,
            function (AdminOrderNotification $mail) {
                return $mail->hasTo('admin@example.test')
                    && $mail->order->is($this->order);
            }
        );
    }

    public function test_listener_does_not_send_email_when_admin_address_is_missing(): void
    {
        Mail::fake();
        $log = Log::spy();

        config(['services.admin_notification.email' => null]);

        $listener = new SendAdminOrderEmail;
        $listener->handle(new OrderCreated($this->order));

        Mail::assertNothingSent();

        $log->shouldHaveReceived('warning')
            ->once()
            ->with(
                'Admin order email notification skipped: recipient is not configured.',
                ['order_id' => $this->order->id]
            );
    }

    public function test_listener_trims_configured_admin_address(): void
    {
        Mail::fake();

        config(['services.admin_notification.email' => '  admin@example.test  ']);

        $listener = new SendAdminOrderEmail;
        $listener->handle(new OrderCreated($this->order));

        Mail::assertSent(
            AdminOrderNotification::class,
            fn (AdminOrderNotification $mail) => $mail->hasTo('admin@example.test')
        );
    }

    public function test_email_renders_order_snapshot_data(): void
    {
        $order = $this->order->load(['user', 'items']);

        $mailable = new AdminOrderNotification($order);

        $mailable->assertHasSubject('Brew & Bite - New Order #'.$order->id);

        $rendered = $mailable->render();

        $this->assertStringContainsString((string) $order->id, $rendered);
        $this->assertStringContainsString('Test Customer', $rendered);
        $this->assertStringContainsString('Black Coffee Snapshot', $rendered);
        $this->assertStringContainsString('2', $rendered);
        $this->assertStringContainsString('30,000 VND', $rendered);
        $this->assertStringContainsString('60,000 VND', $rendered);
        $this->assertStringNotContainsString('Current Product Name', $rendered);
    }
}
