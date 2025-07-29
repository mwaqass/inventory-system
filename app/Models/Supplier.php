<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'address',
        'contact_person',
        'contact_phone',
        'contact_email',
        'lead_time_days',
        'minimum_order_amount',
        'payment_terms',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'minimum_order_amount' => 'decimal:2',
        'lead_time_days' => 'integer',
    ];

    /**
     * Get all products from this supplier
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all purchase orders from this supplier
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Scope to get only active suppliers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the full contact information
     */
    public function getFullContactAttribute()
    {
        $contact = [];

        if ($this->contact_person) {
            $contact[] = $this->contact_person;
        }

        if ($this->contact_phone) {
            $contact[] = $this->contact_phone;
        }

        if ($this->contact_email) {
            $contact[] = $this->contact_email;
        }

        return implode(' | ', $contact);
    }
}
