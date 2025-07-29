<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'description',
        'category_id',
        'supplier_id',
        'cost_price',
        'selling_price',
        'weight',
        'length',
        'width',
        'height',
        'reorder_point',
        'reorder_quantity',
        'unit',
        'attributes',
        'images',
        'is_active',
        'track_serial',
        'track_batch',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'reorder_point' => 'integer',
        'reorder_quantity' => 'integer',
        'attributes' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
        'track_serial' => 'boolean',
        'track_batch' => 'boolean',
    ];

    /**
     * Get the category this product belongs to
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the supplier for this product
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get all warehouses where this product is stored
     */
    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'product_warehouse')
                    ->withPivot([
                        'quantity_on_hand',
                        'quantity_reserved',
                        'quantity_on_order',
                        'quantity_available'
                    ])
                    ->withTimestamps();
    }

    /**
     * Get all stock movements for this product
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get all order items for this product
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all purchase order items for this product
     */
    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Scope to get only active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get products with low stock
     */
    public function scopeLowStock($query)
    {
        return $query->whereHas('warehouses', function ($q) {
            $q->whereRaw('product_warehouse.quantity_on_hand <= products.reorder_point');
        });
    }

    /**
     * Get the total quantity across all warehouses
     */
    public function getTotalQuantityAttribute()
    {
        return $this->warehouses()->sum('product_warehouse.quantity_on_hand');
    }

    /**
     * Get the total available quantity across all warehouses
     */
    public function getTotalAvailableAttribute()
    {
        return $this->warehouses()->sum('product_warehouse.quantity_available');
    }

    /**
     * Check if product is in stock
     */
    public function getInStockAttribute()
    {
        return $this->total_available > 0;
    }

    /**
     * Check if product needs reordering
     */
    public function getNeedsReorderAttribute()
    {
        return $this->total_quantity <= $this->reorder_point;
    }

    /**
     * Get the profit margin
     */
    public function getProfitMarginAttribute()
    {
        if ($this->cost_price > 0) {
            return (($this->selling_price - $this->cost_price) / $this->cost_price) * 100;
        }
        return 0;
    }
}
