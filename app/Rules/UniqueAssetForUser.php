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
    protected $excludeId;

    public function __construct($month, $year, $excludeId = null)
    {
        $this->month = $month;
        $this->year = $year;
        $this->excludeId = $excludeId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = Asset::where('user_id', Auth::id())
            ->where('month', $this->month)
            ->where('year', $this->year);

        // Exclude the current record if we're editing
        if ($this->excludeId) {
            $query->where('id', '!=', $this->excludeId);
        }

        $exists = $query->exists();

        if ($exists) {
            $fail('An asset record already exists for this month and year. Please edit the existing record instead.');
        }
    }
}
