<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'reason',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the return this item belongs to
     */
    public function return(): BelongsTo
    {
        return $this->belongsTo(ProductReturn::class);
    }

    /**
     * Get the product for this item
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate the total price for this item
     */
    public function calculateTotal()
    {
        $this->total_price = $this->unit_price * $this->quantity;
        $this->save();
    }
}
