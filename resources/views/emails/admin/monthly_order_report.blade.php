<x-mail::message>
# Monthly Order Report — {{ $report['month'] }}

Here is the completed-order summary for **{{ $report['month'] }}** on **Brew & Bite**.

---

## Summary

| Metric | Value |
| ------ | ----- |
| **Reporting Month** | {{ $report['month'] }} |
| **Total Completed Orders** | {{ $report['total_orders'] }} |
| **Total Revenue** | {{ number_format((float) $report['total_revenue'], 0, '.', ',') }} VND |
| **Average Order Value** | {{ number_format((float) $report['average_order_value'], 0, '.', ',') }} VND |
| **Total Products Sold** | {{ $report['total_products_sold'] }} |

---

## Top Products

@if (count($report['top_products']) > 0)
<x-mail::table>
| Rank | Product | Quantity Sold |
| ---- | ------- | ------------- |
@foreach ($report['top_products'] as $index => $product)
| {{ $index + 1 }} | {{ $product['product_name'] }} | {{ $product['quantity_sold'] }} |
@endforeach
</x-mail::table>
@else
*No completed orders were recorded for this month.*
@endif

---

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
