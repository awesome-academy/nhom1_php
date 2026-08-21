<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Mail\MonthlyOrderReport;
use App\Services\MonthlyOrderReportService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendMonthlyOrderReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:monthly-orders
        {--month= : Reporting month in YYYY-MM format; defaults to the previous calendar month}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the monthly order statistics report email to the configured admin.';

    /**
     * Execute the console command.
     */
    public function handle(MonthlyOrderReportService $reportService): int
    {
        $reportMonth = $this->resolveReportMonth();

        if ($reportMonth === null) {
            return Command::FAILURE;
        }

        $adminAddress = trim((string) config('services.admin_notification.email'));

        if ($adminAddress === '') {
            $this->error('Admin notification recipient is not configured. Set ADMIN_NOTIFICATION_EMAIL in your environment.');

            return Command::FAILURE;
        }

        $report = $reportService->generate($reportMonth);

        Mail::to($adminAddress)->send(new MonthlyOrderReport($report));

        $this->info(sprintf(
            'Monthly order report for %s sent to %s.',
            $report['month'],
            $adminAddress,
        ));

        return Command::SUCCESS;
    }

    /**
     * Resolve the reporting month from the --month option or default to the previous calendar month.
     *
     * Returns null and outputs an error message if the option value is invalid.
     */
    private function resolveReportMonth(): ?Carbon
    {
        $option = $this->option('month');

        if ($option === null) {
            return Carbon::now()->subMonthNoOverflow()->startOfMonth();
        }

        if (! preg_match('/^(?!0000)\d{4}-(0[1-9]|1[0-2])$/', (string) $option)) {
            $this->error(sprintf(
                'Invalid --month value "%s". Expected format: YYYY-MM (e.g. 2026-08).',
                $option,
            ));

            return null;
        }

        return Carbon::createFromFormat('Y-m', (string) $option)->startOfMonth();
    }
}
