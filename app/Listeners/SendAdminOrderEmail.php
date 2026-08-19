<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\AdminOrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAdminOrderEmail implements ShouldQueue
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
        $adminAddress = trim((string) config('services.admin_notification.email'));

        if ($adminAddress === '') {
            Log::warning(
                'Admin order email notification skipped: recipient is not configured.',
                ['order_id' => $event->order->id],
            );

            return;
        }

        $order = $event->order->loadMissing(['user', 'items']);

        Mail::to($adminAddress)->send(
            new AdminOrderNotification($order),
        );
    }
}
