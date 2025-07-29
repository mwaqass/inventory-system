<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'status',
        'order_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'total_amount',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the supplier for this purchase order
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the user who created this purchase order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all items in this purchase order
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Get all stock movements for this purchase order
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'reference_id')
                    ->where('reference_type', PurchaseOrder::class);
    }

    /**
     * Scope to get purchase orders by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if purchase order can be received
     */
    public function getCanReceiveAttribute()
    {
        return in_array($this->status, ['confirmed', 'sent']);
    }

    /**
     * Check if purchase order is overdue
     */
    public function getIsOverdueAttribute()
    {
        return $this->expected_delivery_date &&
               $this->expected_delivery_date->isPast() &&
               $this->status !== 'received';
    }

    /**
     * Get the purchase order status badge color
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'secondary',
            'sent' => 'info',
            'confirmed' => 'primary',
            'received' => 'success',
            'cancelled' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Calculate purchase order totals
     */
    public function calculateTotals()
    {
        $subtotal = $this->items->sum('total_cost');
        $tax = $this->tax_amount;
        $shipping = $this->shipping_amount;

        $total = $subtotal + $tax + $shipping;

        $this->update([
            'subtotal' => $subtotal,
            'total_amount' => $total,
        ]);
    }
}
