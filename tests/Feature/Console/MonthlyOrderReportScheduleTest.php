<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Tests\TestCase;

/**
 * #98928 – Feature tests for the monthly order report Laravel Scheduler entry.
 *
 * Covers:
 *   - The 'report:monthly-orders' command is registered in the scheduler.
 *   - The cron expression is '0 0 1 * *' (first day of each month at 00:00).
 *   - withoutOverlapping is enabled.
 */
class MonthlyOrderReportScheduleTest extends TestCase
{
    private function getScheduledEvent(): ?Event
    {
        /** @var Schedule $schedule */
        $schedule = $this->app->make(Schedule::class);

        foreach ($schedule->events() as $event) {
            if (str_contains($event->command, 'report:monthly-orders')) {
                return $event;
            }
        }

        return null;
    }

    public function test_report_monthly_orders_is_registered_in_scheduler(): void
    {
        $this->assertNotNull(
            $this->getScheduledEvent(),
            'Expected the report:monthly-orders command to be registered in the scheduler.',
        );
    }

    public function test_report_monthly_orders_has_correct_cron_expression(): void
    {
        $event = $this->getScheduledEvent();

        $this->assertNotNull($event, 'Scheduled event for report:monthly-orders not found.');
        $this->assertSame('0 0 1 * *', $event->expression);
    }

    public function test_report_monthly_orders_has_without_overlapping_enabled(): void
    {
        $event = $this->getScheduledEvent();

        $this->assertNotNull($event, 'Scheduled event for report:monthly-orders not found.');
        $this->assertTrue($event->withoutOverlapping, 'Expected withoutOverlapping to be true.');
    }
}
