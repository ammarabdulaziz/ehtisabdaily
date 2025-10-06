<?php

namespace App\Filament\Resources\Duas\Tables;

use App\Filament\Exports\DuaExporter;
use App\Filament\Imports\DuaImporter;
use App\Filament\Resources\Duas\DuaResource;
use App\Models\Dua;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DuasTable
{
    public static function configure(Table $table): Table
    {
        if (session()->has('duas_modal_record_ids')) {
            session()->remove('duas_modal_record_ids');
        }

        return $table
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->limit(50),

                TextColumn::make('arabic_text')
                    ->label('Arabic Text')
                    ->limit(75)
                    ->extraAttributes(['class' => 'font-arabic'])
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 75) {
                            return null;
                        }

                        return $state;
                    }),

                TagsColumn::make('categories')
                    ->label('Categories')
                    // ->limit(2)
                    ->placeholder('—'),

                /*TagsColumn::make('occasions')
                    ->label('Occasions')
                    ->limit(2)
                    ->placeholder('—'),*/

                TextColumn::make('source')
                    ->label('Source')
                    ->badge()
                    ->color('success')
                    ->placeholder('—'),

                TextColumn::make('recitation_count')
                    ->label('Recitation')
                    ->alignCenter()
                    ->suffix('x'),

                /*IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->alignCenter(),*/

                /*IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->alignCenter(),*/

                /*TagsColumn::make('tags')
                    ->label('Tags')
                    ->limit(2)
                    ->placeholder('—'),*/

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('categories')
                    ->label('Categories')
                    ->options(function () {
                        // Use cached categories if available, fallback to static method
                        $cachedCategories = DuaResource::getCachedCategories();
                        if (!empty($cachedCategories)) {
                            return array_combine($cachedCategories, $cachedCategories);
                        }
                        return Dua::getCategories();
                    })
                    ->multiple()
                    ->query(function (Builder $query, array $data) {
                        if (filled($data['values'])) {
                            foreach ($data['values'] as $category) {
                                $query->whereJsonContains('categories', $category);
                            }
                        }
                    }),

                /*SelectFilter::make('occasions')
                    ->label('Occasions')
                    ->options(Dua::getOccasions())
                    ->multiple()
                    ->query(function (Builder $query, array $data) {
                        if (filled($data['values'])) {
                            foreach ($data['values'] as $occasion) {
                                $query->whereJsonContains('occasions', $occasion);
                            }
                        }
                    }),*/

                SelectFilter::make('source')
                    ->label('Source')
                    ->options(function () {
                        // Use cached sources if available, fallback to static method
                        $cachedSources = DuaResource::getCachedSources();
                        if (!empty($cachedSources)) {
                            return array_combine($cachedSources, $cachedSources);
                        }
                        return Dua::getSources();
                    })
                    ->multiple(),

                /*TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->placeholder('All duas')
                    ->trueLabel('Featured only')
                    ->falseLabel('Not featured'),*/

                /*TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All duas')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),*/
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalWidth('7xl')
                    ->modalHeading(fn ($record) => $record->title)
                    ->extraAttributes(fn($record) => [
                        'class' => 'dua-id-' . $record->id,
                    ])
                    ->schema(function (Table $table) {
                        if (!session()->has('duas_modal_record_ids')) {
                            $query = $table->getLivewire()->getFilteredTableQuery();
                            $recordIds = $query->pluck('id')->toArray();
                            session(['duas_modal_record_ids' => $recordIds]);
                        }

                        return DuaResource::infolist(new \Filament\Schemas\Schema)->getComponents();
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->recordUrl(null)
            ->headerActions([
                ImportAction::make()
                    ->importer(DuaImporter::class)
                    ->label('Import Duas')
                    ->icon('heroicon-o-arrow-up-tray'),
                ExportAction::make()
                    ->exporter(DuaExporter::class)
                    ->label('Export All')
                    ->icon('heroicon-o-arrow-down-tray'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exporter(DuaExporter::class)
                        ->label('Export Selected')
                        ->icon('heroicon-o-arrow-down-tray'),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
