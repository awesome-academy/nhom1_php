<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'total_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            OrderStatus::PENDING,
            OrderStatus::CONFIRMED,
        ], true);
    }

    /**
     * 98919 - Trả về danh sách trạng thái admin được phép chuyển đến từ trạng thái hiện tại.
     *
     * Luồng hợp lệ:
     *   PENDING   → CONFIRMED | CANCELLED
     *   CONFIRMED → PREPARING | CANCELLED
     *   PREPARING → COMPLETED
     *   COMPLETED → (không cho phép)
     *   CANCELLED → (không cho phép)
     *
     * @return OrderStatus[]
     */
    public function allowedAdminTransitions(): array
    {
        return match ($this->status) {
            OrderStatus::PENDING   => [OrderStatus::CONFIRMED, OrderStatus::CANCELLED],
            OrderStatus::CONFIRMED => [OrderStatus::PREPARING, OrderStatus::CANCELLED],
            OrderStatus::PREPARING => [OrderStatus::COMPLETED],
            default                => [],
        };
    }
}
