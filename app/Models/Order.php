<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'billing_address',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the user who created this order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all items in this order
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all stock movements for this order
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'reference_id')
                    ->where('reference_type', Order::class);
    }

    /**
     * Get all returns for this order
     */
    public function returns(): HasMany
    {
        return $this->hasMany(ProductReturn::class);
    }

    /**
     * Scope to get orders by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get orders by payment status
     */
    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    /**
     * Check if order can be cancelled
     */
    public function getCanCancelAttribute()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    /**
     * Check if order can be shipped
     */
    public function getCanShipAttribute()
    {
        return in_array($this->status, ['picked']);
    }

    /**
     * Get the order status badge color
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'picking' => 'primary',
            'picked' => 'success',
            'shipping' => 'primary',
            'shipped' => 'success',
            'delivered' => 'success',
            'cancelled' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Calculate order totals
     */
    public function calculateTotals()
    {
        $subtotal = $this->items->sum('total_price');
        $tax = $this->tax_amount;
        $shipping = $this->shipping_amount;
        $discount = $this->discount_amount;

        $total = $subtotal + $tax + $shipping - $discount;

        $this->update([
            'subtotal' => $subtotal,
            'total_amount' => $total,
        ]);
    }
}
