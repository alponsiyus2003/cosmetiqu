<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'usage_limit',
        'usage_per_user',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_per_user' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function usages()
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('start_date', '<=', now())
                     ->where('end_date', '>=', now());
    }

    // Helpers
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if (Carbon::now()->lt($this->start_date) || Carbon::now()->gt($this->end_date)) {
            return false;
        }

        if ($this->usage_limit && $this->usages()->count() >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function canBeUsedByUser($userId)
    {
        $userUsageCount = $this->usages()->where('user_id', $userId)->count();
        return $userUsageCount < $this->usage_per_user;
    }

    public function calculateDiscount($subtotal)
    {
        if ($subtotal < $this->min_purchase) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = ($subtotal * $this->value) / 100;

            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }

            return $discount;
        } else {
            // Fixed amount
            return min($this->value, $subtotal);
        }
    }

    public function getFormattedValueAttribute()
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        } else {
            return 'Rp ' . number_format($this->value, 0, ',', '.');
        }
    }

    public function getStatusBadgeAttribute()
    {
        if (!$this->is_active) {
            return 'secondary';
        }

        if (Carbon::now()->lt($this->start_date)) {
            return 'info';
        }

        if (Carbon::now()->gt($this->end_date)) {
            return 'danger';
        }

        return 'success';
    }

    public function getStatusTextAttribute()
    {
        if (!$this->is_active) {
            return 'Inactive';
        }

        if (Carbon::now()->lt($this->start_date)) {
            return 'Belum Aktif';
        }

        if (Carbon::now()->gt($this->end_date)) {
            return 'Expired';
        }

        return 'Active';
    }
}
