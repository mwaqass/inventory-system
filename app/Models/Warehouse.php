<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'manager_name',
        'manager_phone',
        'manager_email',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all stock movements for this warehouse
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get all products in this warehouse with their stock levels
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_warehouse')
                    ->withPivot([
                        'quantity_on_hand',
                        'quantity_reserved',
                        'quantity_on_order',
                        'quantity_available'
                    ])
                    ->withTimestamps();
    }

    /**
     * Scope to get only active warehouses
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the total value of inventory in this warehouse
     */
    public function getTotalInventoryValueAttribute()
    {
        return $this->products()->sum(DB::raw('product_warehouse.quantity_on_hand * products.cost_price'));
    }

    /**
     * Get the total number of products in this warehouse
     */
    public function getTotalProductsAttribute()
    {
        return $this->products()->count();
    }

    /**
     * Get products with low stock
     */
    public function getLowStockProductsAttribute()
    {
        return $this->products()
                    ->whereRaw('product_warehouse.quantity_on_hand <= products.reorder_point')
                    ->get();
    }
}
