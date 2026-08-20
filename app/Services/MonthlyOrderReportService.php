<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class MonthlyOrderReportService
{
    /**
     * Generate monthly order statistics report for completed orders.
     *
     * Only orders with status = COMPLETED are counted.
     * The reporting month is determined by orders.updated_at using a
     * half-open interval: updated_at >= $start AND updated_at < $end.
     *
     * @param  CarbonInterface  $month  Any date within the desired month; this object is never mutated.
     * @return array{
     *     month: string,
     *     total_orders: int,
     *     total_revenue: string,
     *     average_order_value: string,
     *     total_products_sold: int,
     *     top_products: list<array{product_id: int, product_name: string, quantity_sold: int}>,
     * }
     */
    public function generate(CarbonInterface $month): array
    {
        // Use immutable copies so the caller's $month is never mutated.
        $start = $month->toImmutable()->startOfMonth();
        $end = $start->addMonth();

        $status = OrderStatus::COMPLETED->value;

        // ── Summary query ──────────────────────────────────────────────────────
        $summary = Order::query()
            ->where('status', $status)
            ->where('updated_at', '>=', $start)
            ->where('updated_at', '<', $end)
            ->select([
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('AVG(total_amount) as average_order_value'),
            ])
            ->first();

        $totalOrders = (int) ($summary->total_orders ?? 0);

        if ($totalOrders === 0) {
            return [
                'month' => $month->format('Y-m'),
                'total_orders' => 0,
                'total_revenue' => '0.00',
                'average_order_value' => '0.00',
                'total_products_sold' => 0,
                'top_products' => [],
            ];
        }

        // ── Order items aggregate ──────────────────────────────────────────────
        $itemStats = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', $status)
            ->where('orders.updated_at', '>=', $start)
            ->where('orders.updated_at', '<', $end)
            ->select(DB::raw('SUM(order_items.quantity) as total_products_sold'))
            ->first();

        // ── Top 5 products ─────────────────────────────────────────────────────
        $topProducts = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', $status)
            ->where('orders.updated_at', '>=', $start)
            ->where('orders.updated_at', '<', $end)
            ->select([
                'order_items.product_id',
                DB::raw('MAX(order_items.product_name) as product_name'),
                DB::raw('SUM(order_items.quantity) as quantity_sold'),
            ])
            ->groupBy('order_items.product_id')
            ->orderByDesc('quantity_sold')
            ->orderBy('order_items.product_id')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'product_id' => (int) $row->product_id,
                'product_name' => (string) $row->product_name,
                'quantity_sold' => (int) $row->quantity_sold,
            ])
            ->values()
            ->all();

        return [
            'month' => $month->format('Y-m'),
            'total_orders' => $totalOrders,
            'total_revenue' => number_format((float) $summary->total_revenue, 2, '.', ''),
            'average_order_value' => number_format((float) $summary->average_order_value, 2, '.', ''),
            'total_products_sold' => (int) ($itemStats->total_products_sold ?? 0),
            'top_products' => $topProducts,
        ];
    }
}
