<?php

namespace App\Models;

use App\Libraries\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'order_number',
        'shipping_address',
        'billing_address',
        'payment_method',
        'payment_status',
        'notes',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            // If order_number is not provided, generate one
            if (empty($order->order_number)) {
                // Generate a unique order number with prefix 'ORD' followed by random string
                $order->order_number = 'ORD' . strtoupper(Str::random(6));
            }
        });
    }

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

}
