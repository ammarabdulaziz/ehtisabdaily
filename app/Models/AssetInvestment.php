<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetInvestment extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_management_id',
        'investment_type_id',
        'amount',
        'exchange_rate',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'exchange_rate' => 'decimal:6',
        ];
    }

    public function assetManagement(): BelongsTo
    {
        return $this->belongsTo(AssetManagement::class);
    }

    public function investmentType(): BelongsTo
    {
        return $this->belongsTo(InvestmentType::class);
    }
}
