<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_number',
        'order_id',
        'status',
        'type',
        'customer_name',
        'customer_email',
        'customer_phone',
        'reason',
        'notes',
        'total_amount',
        'refund_method',
        'user_id',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the order this return is for
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user who created this return
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all items in this return
     */
    public function items(): HasMany
    {
        return $this->hasMany(ReturnItem::class);
    }

    /**
     * Get all stock movements for this return
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'reference_id')
                    ->where('reference_type', ProductReturn::class);
    }

    /**
     * Scope to get returns by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get returns by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Check if return can be approved
     */
    public function getCanApproveAttribute()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if return can be processed
     */
    public function getCanProcessAttribute()
    {
        return $this->status === 'received';
    }

    /**
     * Get the return status badge color
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'approved' => 'info',
            'received' => 'primary',
            'processed' => 'success',
            'cancelled' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Calculate return totals
     */
    public function calculateTotals()
    {
        $total = $this->items->sum('total_price');

        $this->update([
            'total_amount' => $total,
        ]);
    }
}
