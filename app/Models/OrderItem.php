<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'seller_id',
        'product_name',
        'price',
        'original_price',
        'quantity',
        'subtotal',
    ];

    /** কোন order এর item */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** কোন product */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /** কোন seller এর product */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
