<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'user_id',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'reference_type',
        'reference_id',
        'batch_number',
        'serial_number',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
    ];

    /**
     * Get the product for this movement
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the warehouse for this movement
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the user who made this movement
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reference model (Order, PurchaseOrder, etc.)
     */
    public function reference()
    {
        return $this->morphTo();
    }

    /**
     * Scope to get movements by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get movements for a specific product
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope to get movements for a specific warehouse
     */
    public function scopeForWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    /**
     * Get the movement description
     */
    public function getDescriptionAttribute()
    {
        $descriptions = [
            'in' => 'Stock In',
            'out' => 'Stock Out',
            'adjustment' => 'Stock Adjustment',
            'transfer' => 'Stock Transfer',
        ];

        return $descriptions[$this->type] ?? 'Unknown Movement';
    }

    /**
     * Get the movement impact (positive or negative)
     */
    public function getImpactAttribute()
    {
        return $this->quantity_after - $this->quantity_before;
    }
}
