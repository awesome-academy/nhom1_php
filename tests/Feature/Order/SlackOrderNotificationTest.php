<?php

namespace Tests\Feature\Order;

use App\Enums\OrderStatus;
use App\Events\OrderCreated;
use App\Listeners\SendSlackOrderNotification;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SlackOrderNotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Order $order;

    private OrderItem $orderItem;

    private string $webhookUrl = 'https://hooks.slack.test/services/order-notifications';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
        ]);

        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Black Coffee',
            'price' => 30000,
        ]);

        $this->order = Order::create([
            'user_id' => $this->user->id,
            'status' => OrderStatus::PENDING,
            'total_amount' => 60000,
        ]);

        $this->orderItem = OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $product->id,
            'product_name' => 'Black Coffee',
            'quantity' => 2,
            'unit_price' => 30000,
            'subtotal' => 60000,
        ]);
    }

    public function test_listener_sends_request_when_webhook_is_configured(): void
    {
        Config::set('services.slack_order_webhook.url', $this->webhookUrl);
        Http::fake([
            $this->webhookUrl => Http::response('ok', 200),
        ]);

        $event = new OrderCreated($this->order);
        $listener = new SendSlackOrderNotification;
        $listener->handle($event);

        Http::assertSentCount(1);
        Http::assertSent(function ($request) {
            $data = $request->data();
            $text = $data['text'] ?? '';

            return $request->method() === 'POST' &&
                $request->url() === $this->webhookUrl &&
                str_contains($text, "New order #{$this->order->id}") &&
                str_contains($text, 'Test Customer') &&
                str_contains($text, 'pending') &&
                str_contains($text, 'Black Coffee x2 - 60,000.00 VND') &&
                str_contains($text, 'Total:* 60,000.00 VND') &&
                ! str_contains($text, 'customer@test.com');
        });
    }

    public function test_listener_skips_when_webhook_is_not_configured(): void
    {
        Config::set('services.slack_order_webhook.url', null);
        Http::fake();
        Log::spy();

        $event = new OrderCreated($this->order);
        $listener = new SendSlackOrderNotification;
        $listener->handle($event);

        Http::assertNothingSent();

        Log::shouldHaveReceived('warning')
            ->once()
            ->with('Slack order notification was skipped because the webhook URL is not configured.', [
                'order_id' => $this->order->id,
            ]);
    }

    public function test_listener_throws_exception_when_slack_returns_500(): void
    {
        Config::set('services.slack_order_webhook.url', $this->webhookUrl);
        Http::fake([
            $this->webhookUrl => Http::response('error', 500),
        ]);

        $this->expectException(RequestException::class);

        $event = new OrderCreated($this->order);
        $listener = new SendSlackOrderNotification;
        $listener->handle($event);
    }
}
