<x-mail::message>
# New Order Received — #{{ $order->id }}

A new order has been placed on **Brew & Bite**.

**Order ID:** #{{ $order->id }}
**Placed At:** {{ $order->created_at->format('d/m/Y H:i:s') }}
**Status:** {{ $order->status->value }}
**Customer:** {{ $order->user->name }}

---

## Order Items

<x-mail::table>
| Product | Qty | Unit Price | Subtotal |
| ------- | --- | ---------- | -------- |
@foreach ($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | {{ number_format((float) $item->unit_price, 0, '.', ',') }} VND | {{ number_format((float) $item->subtotal, 0, '.', ',') }} VND |
@endforeach
</x-mail::table>

---

**Total Amount: {{ number_format((float) $order->total_amount, 0, '.', ',') }} VND**

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
