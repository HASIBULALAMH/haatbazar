<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'payment_method',
        'payment_status',
        'transaction_id',
        'status',
        'subtotal',
        'shipping_charge',
        'total',
        'notes',
    ];

    /** Order টি কোন buyer এর */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Order এর সব items */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** Status badge color — blade এ use হবে */
    public function statusColor(): string
    {
        return match($this->status) {
            'pending'    => '#fbbf24',
            'processing' => '#a5b4fc',
            'shipped'    => '#38bdf8',
            'delivered'  => '#86efac',
            'cancelled'  => '#fca5a5',
            default      => '#6b7280',
        };
    }

    /** Payment status color */
    public function paymentColor(): string
    {
        return match($this->payment_status) {
            'paid'   => '#86efac',
            'failed' => '#fca5a5',
            default  => '#fbbf24',
        };
    }
}
