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
        'investment_type',
        'investment_name',
        'amount',
        'currency',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function assetManagement(): BelongsTo
    {
        return $this->belongsTo(AssetManagement::class);
    }
}
