<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        // TODO: Implement Email notification (Task #98926)
    }
}
