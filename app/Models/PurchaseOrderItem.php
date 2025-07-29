<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'unit_cost',
        'total_cost',
        'received_quantity',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'received_quantity' => 'integer',
    ];

    /**
     * Get the purchase order this item belongs to
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Get the product for this item
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate the total cost for this item
     */
    public function calculateTotal()
    {
        $this->total_cost = $this->unit_cost * $this->quantity;
        $this->save();
    }

    /**
     * Get the remaining quantity to receive
     */
    public function getRemainingQuantityAttribute()
    {
        return $this->quantity - $this->received_quantity;
    }

    /**
     * Check if item is fully received
     */
    public function getIsFullyReceivedAttribute()
    {
        return $this->received_quantity >= $this->quantity;
    }
}
