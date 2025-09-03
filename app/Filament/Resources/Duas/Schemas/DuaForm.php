<?php

namespace App\Filament\Resources\Duas\Schemas;

use App\Models\Dua;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DuaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*Section::make('AI-Powered Dua Generation')
                    ->description('Enter a glimpse of the dua and let AI help populate the form fields')
                    ->schema([
                        TextInput::make('ai_glimpse')
                            ->label('Dua Glimpse')
                            ->placeholder('Enter a glimpse or description of the dua you want to create...')
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->helperText('Enter a description of the dua you want to create, and the AI will help populate the form fields below.'),
                    ])
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed(),*/

                Section::make('Basic Information')
                    ->schema([
                        // Hidden::make('user_id')
                        //     ->default(fn () => Filament::auth()->id()),

                        Grid::make()
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('title')
                                    ->label('Title')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g., Dua before eating'),

                                TagsInput::make('categories')
                                    ->label('Categories')
                                    ->suggestions(Dua::getCategories())
                                    ->placeholder('Type categories or select from suggestions')
                                    ->dehydrateStateUsing(fn ($state) => collect($state)->map(fn ($tag) => ucfirst($tag))->toArray()),

                            ]),

                        Grid::make()
                            ->schema([
                                /*TagsInput::make('occasions')
                                    ->label('Occasions')
                                    ->suggestions(Dua::getOccasions())
                                    ->placeholder('Type occasions or select from suggestions'),*/

                                TextInput::make('recitation_count')
                                    ->label('Recitation Count')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->maxValue(100)
                                    ->suffix('times'),

                                TextInput::make('sort_order')
                                    ->label('Sort Order (Optional - Auto-set if empty)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(9999)
                                    ->placeholder('Leave empty for auto-assignment'),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->collapsible(),

                Section::make('Arabic Text & Translation')
                    ->schema([
                        Textarea::make('arabic_text')
                            ->label('Arabic Text')
                            ->required()
                            ->rows(4)
                            ->placeholder('Enter the dua in Arabic')
                            ->columnSpanFull()
                            ->extraAttributes(['dir' => 'rtl']),

                        Textarea::make('english_translation')
                            ->label('English Translation (Optional)')
                            ->rows(3)
                            ->placeholder('Direct translation of the dua')
                            ->columnSpanFull(),

                        Textarea::make('transliteration')
                            ->label('Transliteration (Optional)')
                            ->rows(3)
                            ->placeholder('Phonetic pronunciation guide')
                            ->columnSpanFull(),

                        Textarea::make('english_meaning')
                            ->label('Detailed Meaning (Optional)')
                            ->rows(4)
                            ->placeholder('Detailed explanation or context')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->collapsible(),

                Section::make('Source & Reference (Optional)')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Select::make('source')
                                    ->label('Source')
                                    ->options(Dua::getSources())
                                    ->searchable()
                                    ->placeholder('Select source (optional)'),

                                TextInput::make('reference')
                                    ->label('Reference')
                                    ->placeholder('e.g., Surah Al-Fatiha 1:1 or Sahih Bukhari 123')
                                    ->maxLength(255),
                            ]),

                        Textarea::make('benefits')
                            ->label('Benefits & Virtues (Optional)')
                            ->rows(3)
                            ->placeholder('Benefits or virtues of reciting this dua')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed(),

                /*Section::make('Organization & Settings (Optional)')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('sort_order')
                                    ->label('Sort Order (Optional - Auto-set if empty)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(9999)
                                    ->placeholder('Leave empty for auto-assignment'),

                                Toggle::make('is_featured')
                                    ->label('Featured Dua')
                                    ->default(false)
                                    ->inline(false),

                                Toggle::make('is_active')
                                    ->label('Active Status')
                                    ->default(true)
                                    ->inline(false),
                            ]),

                        TagsInput::make('tags')
                            ->label('Tags (Optional)')
                            ->placeholder('Add relevant tags for better organization')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsible()
                    ->collapsed(),*/
            ]);
    }
}
