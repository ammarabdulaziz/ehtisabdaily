<?php

namespace App\Rules;

use App\Models\Asset;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class UniqueAssetForUser implements ValidationRule
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = Asset::where('user_id', Auth::id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->exists();
            
        if ($exists) {
            $fail('An asset record already exists for this month and year. Please edit the existing record instead.');
        }
    }
}
