<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonthlyOrderReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  array{
     *     month: string,
     *     total_orders: int,
     *     total_revenue: string,
     *     average_order_value: string,
     *     total_products_sold: int,
     *     top_products: list<array{product_id: int, product_name: string, quantity_sold: int}>,
     * }  $report
     */
    public function __construct(public readonly array $report)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Brew & Bite - Monthly Order Report '.$this->report['month'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin.monthly_order_report',
        );
    }
}
