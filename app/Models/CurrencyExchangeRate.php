<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CurrencyExchangeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_currency_id',
        'to_currency_id',
        'rate',
        'date',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:6',
            'date' => 'date',
        ];
    }

    public function fromCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    public function toCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('date', '<=', $date)->orderBy('date', 'desc');
    }

    public function scopeForCurrencies($query, $fromCode, $toCode)
    {
        return $query->whereHas('fromCurrency', function ($q) use ($fromCode) {
            $q->where('code', $fromCode);
        })->whereHas('toCurrency', function ($q) use ($toCode) {
            $q->where('code', $toCode);
        });
    }
}
