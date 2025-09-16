<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetManagement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'month' => 'integer',
            'year' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(AssetAccount::class);
    }

    public function lentMoney(): HasMany
    {
        return $this->hasMany(AssetLentMoney::class);
    }

    public function borrowedMoney(): HasMany
    {
        return $this->hasMany(AssetBorrowedMoney::class);
    }

    public function investments(): HasMany
    {
        return $this->hasMany(AssetInvestment::class);
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(AssetDeposit::class);
    }

    public function getTotalAccountsAttribute(): float
    {
        return $this->accounts->sum(function ($account) {
            return $this->convertToQAR($account->amount, $account->currency);
        });
    }

    public function getTotalLentMoneyAttribute(): float
    {
        return $this->lentMoney->sum(function ($lent) {
            return $this->convertToQAR($lent->amount, $lent->currency);
        });
    }

    public function getTotalBorrowedMoneyAttribute(): float
    {
        return $this->borrowedMoney->sum(function ($borrowed) {
            return $this->convertToQAR($borrowed->amount, $borrowed->currency);
        });
    }

    public function getTotalInvestmentsAttribute(): float
    {
        return $this->investments->sum(function ($investment) {
            return $this->convertToQAR($investment->amount, $investment->currency);
        });
    }

    public function getTotalDepositsAttribute(): float
    {
        return $this->deposits->sum(function ($deposit) {
            return $this->convertToQAR($deposit->amount, $deposit->currency);
        });
    }

    public function getTotalInHandAttribute(): float
    {
        return $this->accounts->where('accountType.name', 'Cash-in-Hand')->sum(function ($account) {
            return $this->convertToQAR($account->amount, $account->currency);
        });
    }

    public function getGrandTotalAttribute(): float
    {
        return $this->total_accounts + $this->total_lent_money + $this->total_investments + $this->total_deposits;
    }

    public function getSavingsAttribute(): float
    {
        $previousMonth = static::where('user_id', $this->user_id)
            ->where('year', $this->year)
            ->where('month', $this->month - 1)
            ->first();

        if (!$previousMonth) {
            $previousMonth = static::where('user_id', $this->user_id)
                ->where('year', $this->year - 1)
                ->where('month', 12)
                ->first();
        }

        if (!$previousMonth) {
            return 0;
        }

        return $previousMonth->grand_total - $this->grand_total;
    }

    private function convertToQAR(float $amount, string $currency): float
    {
        if ($currency === 'QAR') {
            return $amount;
        }

        $exchangeRate = CurrencyExchangeRate::where('from_currency_id', function ($query) use ($currency) {
            $query->select('id')->from('currencies')->where('code', $currency);
        })
        ->where('to_currency_id', function ($query) {
            $query->select('id')->from('currencies')->where('code', 'QAR');
        })
        ->where('date', '<=', now()->toDateString())
        ->orderBy('date', 'desc')
        ->value('rate');

        return $exchangeRate ? $amount * $exchangeRate : $amount;
    }

    public function getMonthNameAttribute(): string
    {
        return now()->month($this->month)->format('F');
    }

    public function getFormattedPeriodAttribute(): string
    {
        return $this->month_name . ' ' . $this->year;
    }
}
