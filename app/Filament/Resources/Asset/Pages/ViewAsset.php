<?php

namespace App\Filament\Resources\Asset\Pages;

use App\Filament\Resources\Asset\AssetResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Asset\Widgets\AssetsReminderWidget;

class ViewAsset extends ViewRecord
{
    protected static string $resource = AssetResource::class;

    protected string $view = 'filament.pages.view-asset';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('lockPage')
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

    protected function getHeaderWidgets(): array
    {
        return [
            AssetsReminderWidget::class,
        ];
    }
}
