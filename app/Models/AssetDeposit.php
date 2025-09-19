<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_management_id',
        'deposit_type_id',
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

    public function depositType(): BelongsTo
    {
        return $this->belongsTo(DepositType::class);
    }
}
