<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_management_id',
        'account_type_id',
        'actual_amount',
        'amount',
        'currency',
        'exchange_rate',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'actual_amount' => 'decimal:2',
            'amount' => 'decimal:2',
            'currency' => 'string',
            'exchange_rate' => 'decimal:6',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($model) {
            $model->calculateAndSetAmount();
        });
    }

    public function assetManagement(): BelongsTo
    {
        return $this->belongsTo(AssetManagement::class);
    }

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    /**
     * Get the calculated amount (actual_amount / exchange_rate).
     */
    public function getAmountAttribute(): ?float
    {
        if ($this->actual_amount === null || $this->exchange_rate === null || $this->exchange_rate == 0) {
            return null;
        }

        return $this->actual_amount / $this->exchange_rate;
    }

    /**
     * Calculate and set the amount based on actual_amount and exchange_rate.
     */
    protected function calculateAndSetAmount(): void
    {
        if ($this->actual_amount !== null && $this->exchange_rate !== null && $this->exchange_rate != 0) {
            $this->amount = $this->actual_amount / $this->exchange_rate;
        } else {
            $this->amount = null;
        }
    }
}
