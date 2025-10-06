<?php

namespace App\Filament\Resources\Duas;

use App\Filament\Resources\Duas\Pages\CreateDua;
use App\Filament\Resources\Duas\Pages\EditDua;
use App\Filament\Resources\Duas\Pages\ListDuas;
use App\Filament\Resources\Duas\Schemas\DuaForm;
use App\Filament\Resources\Duas\Tables\DuasTable;
use App\Models\Dua;
use App\Services\DuaCacheService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class DuaResource extends Resource
{
    protected static ?string $model = Dua::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $recordTitleAttribute = 'title';

    protected static UnitEnum|string|null $navigationGroup = 'Islamic Content';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Filament::auth()->check()) {
            $query->where('user_id', Filament::auth()->id());
        }

        $query->orderBy('sort_order');

        return $query;
    }

    /**
     * Get cached duas for the current user
     */
    public static function getCachedDuas(): \Illuminate\Database\Eloquent\Collection
    {
        $cacheService = app(DuaCacheService::class);
        return $cacheService->getAllDuasForUser(Filament::auth()->id());
    }

    /**
     * Get cached duas count for the current user
     */
    public static function getCachedDuasCount(): int
    {
        $cacheService = app(DuaCacheService::class);
        return $cacheService->getDuasCount(Filament::auth()->id());
    }

    /**
     * Get cached categories for the current user
     */
    public static function getCachedCategories(): array
    {
        $cacheService = app(DuaCacheService::class);
        return $cacheService->getCategoriesForUser(Filament::auth()->id());
    }

    /**
     * Get cached sources for the current user
     */
    public static function getCachedSources(): array
    {
        $cacheService = app(DuaCacheService::class);
        return $cacheService->getSourcesForUser(Filament::auth()->id());
    }

    public static function form(Schema $schema): Schema
    {
        return DuaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DuasTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([

                // Arabic Text Section
                Section::make('Arabic Text')
                    // ->description('The original Arabic text of the dua')
                    ->schema([
                        Infolists\Components\TextEntry::make('arabic_text')
                            ->hiddenLabel()
                            ->formatStateUsing(fn (string $state): string => $state)
                            ->extraAttributes([
                                'dir' => 'rtl',
                                'style' => "line-height: 2; font-size: 1.25rem; text-align: right;",
                                'class' => 'font-arabic text-gray-800 dark:text-gray-200 bg-gradient-to-r from-green-50 to-blue-50 dark:from-gray-800 dark:to-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700',
                            ]),
                    ])
                    ->icon(Heroicon::CheckCircle)
                    ->columnSpanFull()
                    ->iconColor('success'),

                // Transliteration Section
                /*Section::make('Transliteration')
                    // ->description('Phonetic pronunciation guide')
                    ->schema([
                        Infolists\Components\TextEntry::make('transliteration')
                            ->hiddenLabel()
                            ->placeholder('No transliteration available')
                            ->extraAttributes([
                                'class' => 'text-base italic text-gray-700 dark:text-gray-300 bg-yellow-50 dark:bg-gray-800 p-3 rounded border leading-relaxed',
                            ]),
                    ])
                    ->icon(Heroicon::SpeakerWave)
                    ->iconColor('warning')
                    // ->hidden(fn ($record) => blank($record?->transliteration))
                    ->collapsible(),*/

                // English Translation Section
                Section::make('English Translation')
                    // ->description('Direct translation of the Arabic text')
                    ->schema([
                        Infolists\Components\TextEntry::make('english_translation')
                            ->hiddenLabel()
                            ->placeholder('No translation available')
                            ->extraAttributes([
                                'class' => 'text-base text-gray-700 dark:text-gray-300 bg-blue-50 dark:bg-gray-800 p-3 rounded border leading-relaxed',
                            ]),
                    ])
                    ->icon(Heroicon::Language)
                    ->columnSpanFull()
                    ->iconColor('info')
                    // ->hidden(fn ($record) => blank($record?->english_translation))
                    ->collapsible(),

                Grid::make()
                    ->columns([
                        'default' => 2,
                    ])
                    ->extraAttributes(function ($record) {
                        $recordIds = session('duas_modal_record_ids', []);
                        $currentIndex = array_search($record->id, $recordIds);

                        return [
                            'x-data' => sprintf('duaNavigation(%s, %d, %d)',
                                json_encode($recordIds),
                                $record->id,
                                $currentIndex !== false ? $currentIndex : -1
                            ),
                            'x-on:navigate-previous' => 'navigateToPrevious()',
                            'x-on:navigate-next' => 'navigateToNext()',
                        ];
                    })
                    ->schema([
                        Action::make('previous')
                            ->label('Previous')
                            ->icon(Heroicon::ChevronLeft)
                            ->color('gray')
                            ->extraAttributes([
                                'style' => 'width: 100%',
                                'onclick' => 'event.preventDefault(); event.stopPropagation(); this.closest(\'[x-data]\').dispatchEvent(new CustomEvent(\'navigate-previous\'));',
                            ])
                            ->url('#')
                            ->disabled(function ($record) {
                                $recordIds = session('duas_modal_record_ids', []);
                                $currentIndex = array_search($record->id, $recordIds);

                                return $currentIndex === false || $currentIndex === 0;
                            }),

                        Action::make('next')
                            ->label('Next')
                            ->icon(Heroicon::ChevronRight)
                            ->color('gray')
                            ->extraAttributes([
                                'style' => 'width: 100%',
                                'onclick' => 'event.preventDefault(); event.stopPropagation(); this.closest(\'[x-data]\').dispatchEvent(new CustomEvent(\'navigate-next\'));',
                            ])
                            ->url('#')
                            ->disabled(function ($record) {
                                $recordIds = session('duas_modal_record_ids', []);
                                $currentIndex = array_search($record->id, $recordIds);

                                return $currentIndex === false || $currentIndex === count($recordIds) - 1;
                            }),
                    ]),

                // Detailed Meaning Section
                /*Section::make('Detailed Meaning')
                    // ->description('Comprehensive explanation and context')
                    ->schema([
                        Infolists\Components\TextEntry::make('english_meaning')
                            ->hiddenLabel()
                            ->placeholder('No detailed meaning available')
                            ->extraAttributes([
                                'class' => 'text-sm text-gray-700 dark:text-gray-300 bg-purple-50 dark:bg-gray-800 p-3 rounded border leading-relaxed',
                            ]),
                    ])
                    ->icon(Heroicon::InformationCircle)
                    ->iconColor('primary')
                    // ->hidden(fn ($record) => blank($record?->english_meaning))
                    ->collapsible(),*/

                // Categories and Metadata
                /*Section::make('Classification & Details')
                    // ->description('Categories, occasions, and reference information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                // Left Column
                                Grid::make(1)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('categories')
                                            ->label('Categories')
                                            ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : ($state ?? 'No categories'))
                                            ->extraAttributes([
                                                'class' => 'text-sm bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-2 py-1 rounded',
                                            ]),

                                        Infolists\Components\TextEntry::make('occasions')
                                            ->label('Occasions')
                                            ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : ($state ?? 'No occasions'))
                                            ->extraAttributes([
                                                'class' => 'text-sm bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-2 py-1 rounded',
                                            ]),
                                    ]),

                                // Right Column
                                Grid::make(1)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('source')
                                            ->label('Source')
                                            ->badge()
                                            ->color('success')
                                            ->placeholder('No source'),

                                        Infolists\Components\TextEntry::make('reference')
                                            ->label('Reference')
                                            ->placeholder('No reference')
                                            ->extraAttributes([
                                                'class' => 'text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 p-2 rounded',
                                            ]),
                                        // ->hidden(fn ($record) => blank($record?->reference)),

                                        Infolists\Components\TextEntry::make('recitation_count')
                                            ->label('Recitation Count')
                                            ->badge()
                                            ->color('warning')
                                            ->suffix('x')
                                            ->placeholder('1x'),
                                        // ->hidden(fn ($record) => blank($record?->recitation_count) || $record?->recitation_count <= 1),
                                    ]),
                            ]),
                    ])
                    ->collapsible(),*/

                // Benefits Section
                /*Section::make('Benefits & Virtues')
                    // ->description('Spiritual benefits and virtues of reciting this dua')
                    ->schema([
                        Infolists\Components\TextEntry::make('benefits')
                            ->hiddenLabel()
                            ->placeholder('No benefits information available')
                            ->extraAttributes([
                                'class' => 'text-sm text-gray-700 dark:text-gray-300 bg-emerald-50 dark:bg-gray-800 p-3 rounded border leading-relaxed',
                            ]),
                    ])
                    ->icon(Heroicon::Star)
                    ->iconColor('warning')
                    // ->hidden(fn ($record) => blank($record?->benefits))
                    ->collapsible(),*/

                // Tags Section
                /*Section::make('Tags')
                    // ->description('Related keywords and topics')
                    ->schema([
                        Infolists\Components\TextEntry::make('tags')
                            ->hiddenLabel()
                            ->badge()
                            ->formatStateUsing(fn ($state) => collect($state)->map(fn ($tag) => "#{$tag}")->implode(', '))
                            ->placeholder('No tags')
                            ->extraAttributes([
                                'class' => 'text-sm text-gray-600 dark:text-gray-400',
                            ]),
                    ])
                    // ->hidden(fn ($record) => blank($record?->tags))
                    ->collapsible(),*/
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDuas::route('/'),
            'create' => CreateDua::route('/create'),
            'edit' => EditDua::route('/{record}/edit'),
        ];
    }
}
