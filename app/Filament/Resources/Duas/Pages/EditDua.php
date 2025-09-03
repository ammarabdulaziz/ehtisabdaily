<?php

namespace App\Filament\Resources\Duas\Pages;

use App\Filament\Resources\Duas\DuaResource;
use App\Models\Dua;
use App\Services\GeminiService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditDua extends EditRecord
{
    protected static string $resource = DuaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateWithAi')
                ->label('Generate with AI')
                ->icon('heroicon-m-sparkles')
                ->color('primary')
                ->modalHeading('Generate Dua with AI')
                ->modalDescription('Enter a glimpse of the dua and let AI help populate the form fields.')
                ->form([
                    TextInput::make('glimpse')
                        ->label('Dua Glimpse')
                        ->placeholder('Enter a glimpse or description of the dua you want to create...')
                        ->required()
                        ->maxLength(500)
                        ->columnSpanFull(),
                ])
                ->action(function (array $data) {
                    $this->generateContent($data['glimpse']);
                })
                ->modalSubmitActionLabel('Generate Content')
                ->closeModalByClickingAway(false)
                ->modalCancelAction(false),

            DeleteAction::make(),
        ];
    }

    public function generateContent(string $glimpse): void
    {
        try {
            if (empty($glimpse)) {
                Notification::make()
                    ->title('No glimpse provided')
                    ->body('Please enter a description of the dua you want to create.')
                    ->warning()
                    ->send();
                return;
            }

            $geminiService = app(GeminiService::class);
            $aiData = $geminiService->generateDuaContent($glimpse);

            if (! empty($aiData)) {
                // Fill the form with AI-generated data
                $this->form->fill($aiData);

                Notification::make()
                    ->title('AI content generated successfully!')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Failed to generate AI content')
                    ->body('Please try again or check your API configuration.')
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            // Log the actual error details
            Log::error('AI content generation error in EditDua', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Filament::auth()->id(),
                'dua_id' => $this->record->id ?? null,
                'form_state' => $this->form->getState(),
            ]);

            Notification::make()
                ->title('Error generating AI content')
                ->body('An unexpected error occurred. Please try again.')
                ->danger()
                ->send();
        }
    }

    public function canEdit(Dua $record): bool
    {
        return Filament::auth()->check() && $record->user_id === Filament::auth()->id();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_id'] = Filament::auth()->id();

        return $data;
    }
}
