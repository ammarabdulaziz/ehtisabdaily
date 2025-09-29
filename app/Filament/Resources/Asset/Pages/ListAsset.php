<?php

namespace App\Filament\Resources\Asset\Pages;

use App\Filament\Resources\Asset\AssetResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListAsset extends ListRecords
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('lockPage')
                ->label('Lock Page')
                ->icon('heroicon-o-lock-closed')
                ->color('danger')
                ->action(function () {
                    session(['assets_security_locked' => true]);
                    // Clear security session when locked
                    session()->forget(['assets_security_code', 'assets_security_timestamp']);
                    
                    Notification::make()
                        ->title('Page Locked')
                        ->body('This page is now secured. A security code will be required to access it.')
                        ->warning()
                        ->send();
                    
                    // Redirect to security page
                    $this->redirect(route('filament.hisabat.pages.assets-security'));
                })
                ->requiresConfirmation()
                ->modalHeading('Lock Page')
                ->modalDescription('Are you sure you want to lock this page? A security code will be required to access it.')
                ->modalSubmitActionLabel('Lock'),
        ];
    }
}
