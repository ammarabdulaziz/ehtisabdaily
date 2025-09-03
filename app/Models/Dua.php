<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dua extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'arabic_text',
        'transliteration',
        'english_translation',
        'english_meaning',
        'categories',
        'source',
        'reference',
        'benefits',
        'recitation_count',
        'sort_order',
    ];

    protected $casts = [
        'categories' => 'array',
        'recitation_count' => 'integer',
        'sort_order' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Dua $dua) {
            if (is_null($dua->sort_order)) {
                $dua->sort_order = static::max('sort_order') + 1;
            }
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    /*public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }*/

    /*public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }*/

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->whereJsonContains('categories', $category);
    }

    /*public function scopeByOccasion(Builder $query, string $occasion): Builder
    {
        return $query->whereJsonContains('occasions', $occasion);
    }*/

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    // Accessors
    public function getFormattedRecitationCountAttribute(): string
    {
        return $this->recitation_count === 1
            ? 'Once'
            : "{$this->recitation_count} times";
    }

    // Helper methods
    public static function getCategories(): array
    {
        return [
            'Daily Duas' => 'Daily Duas',
            'Morning Duas' => 'Morning Duas',
            'Evening Duas' => 'Evening Duas',
            'Food & Drink' => 'Food & Drink',
            'Travel' => 'Travel',
            'Sleep & Waking' => 'Sleep & Waking',
            'Health & Healing' => 'Health & Healing',
            'Protection' => 'Protection',
            'Forgiveness' => 'Forgiveness',
            'Gratitude' => 'Gratitude',
            'Knowledge' => 'Knowledge',
            'Family' => 'Family',
            'Special Occasions' => 'Special Occasions',
            'Quran Verses' => 'Quran Verses',
            'Prophetic Duas' => 'Prophetic Duas',
        ];
    }

    public static function getOccasions(): array
    {
        return [
            'Before Sleep' => 'Before Sleep',
            'Upon Waking' => 'Upon Waking',
            'Before Eating' => 'Before Eating',
            'After Eating' => 'After Eating',
            'Before Travel' => 'Before Travel',
            'During Travel' => 'During Travel',
            'Before Prayer' => 'Before Prayer',
            'After Prayer' => 'After Prayer',
            'When Ill' => 'When Ill',
            'When Afraid' => 'When Afraid',
            'When Angry' => 'When Angry',
            'When Happy' => 'When Happy',
            'When Sad' => 'When Sad',
            'When in Need' => 'When in Need',
            'When Shopping' => 'When Shopping',
            'Before Study' => 'Before Study',
            'Before Work' => 'Before Work',
            'Friday' => 'Friday',
            'Ramadan' => 'Ramadan',
            'Eid' => 'Eid',
        ];
    }

    public static function getSources(): array
    {
        return [
            'Quran' => 'Quran',
            'Hadith' => 'Hadith',
            'Sahih Bukhari' => 'Sahih Bukhari',
            'Sahih Muslim' => 'Sahih Muslim',
            'Sunan Abu Dawood' => 'Sunan Abu Dawood',
            'Jami at-Tirmidhi' => 'Jami at-Tirmidhi',
            'Sunan an-Nasa\'i' => 'Sunan an-Nasa\'i',
            'Sunan Ibn Majah' => 'Sunan Ibn Majah',
            'Musnad Ahmad' => 'Musnad Ahmad',
            'Hisnul Muslim' => 'Hisnul Muslim',
            'Other Authentic Sources' => 'Other Authentic Sources',
        ];
    }
}
