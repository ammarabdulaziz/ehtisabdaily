<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'is_base',
    ];

    protected function casts(): array
    {
        return [
            'is_base' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($currency) {
            if ($currency->is_base) {
                // Remove base status from all other currencies
                static::where('id', '!=', $currency->id)
                    ->update(['is_base' => false]);
            }
        });
    }

    public function fromExchangeRates(): HasMany
    {
        return $this->hasMany(CurrencyExchangeRate::class, 'from_currency_id');
    }

    public function toExchangeRates(): HasMany
    {
        return $this->hasMany(CurrencyExchangeRate::class, 'to_currency_id');
    }

    public function scopeBase($query)
    {
        return $query->where('is_base', true);
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }
}
