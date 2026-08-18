<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSlackOrderNotification implements ShouldQueue
{
    /**
     * Determine whether events should be dispatched after all transactions are committed.
     */
    public bool $afterCommit = true;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $webhookUrl = config('services.slack_order_webhook.url');

        if (! is_string($webhookUrl) || empty($webhookUrl) || ! filter_var($webhookUrl, FILTER_VALIDATE_URL)) {
            Log::warning('Slack order notification was skipped because the webhook URL is not configured.', [
                'order_id' => $event->order->id,
            ]);

            return;
        }

        $order = $event->order;
        $order->loadMissing(['user', 'items']);

        Http::timeout(5)
            ->post($webhookUrl, [
                'text' => $this->buildMessage($order),
            ])
            ->throw();
    }

    private function buildMessage(Order $order): string
    {
        $message = ":bell: *New order #{$order->id}*\n";
        $message .= "*Customer:* {$order->user->name}\n";
        $message .= "*Status:* {$order->status->value}\n";
        $message .= "*Items:*\n";

        foreach ($order->items as $item) {
            $formattedSubtotal = number_format((float) $item->subtotal, 2, '.', ',');
            $message .= "- {$item->product_name} x{$item->quantity} - {$formattedSubtotal} VND\n";
        }

        $formattedTotal = number_format((float) $order->total_amount, 2, '.', ',');
        $message .= "*Total:* {$formattedTotal} VND";

        return $message;
    }
}
