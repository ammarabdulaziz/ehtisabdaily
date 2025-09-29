<?php

namespace App\Filament\Resources\Asset\Pages;

use App\Filament\Resources\Asset\AssetResource;
use App\Models\Asset;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\UniqueConstraintViolationException;

class CreateAsset extends CreateRecord
{
    protected static string $resource = AssetResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure user_id is set
        if (!isset($data['user_id']) || $data['user_id'] === null) {
            $data['user_id'] = Auth::id();
        }
        
        // Calculate the amount field for each related record
        $this->calculateAmounts($data);
        
        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        try {
            return parent::handleRecordCreation($data);
        } catch (UniqueConstraintViolationException $e) {
            // Check if this is the specific unique constraint we're handling
            if (str_contains($e->getMessage(), 'UNIQUE constraint failed: assets.user_id, assets.month, assets.year')) {
                // Find the existing asset and redirect to edit it
                $existingAsset = Asset::where('user_id', $data['user_id'])
                    ->where('month', $data['month'])
                    ->where('year', $data['year'])
                    ->first();
                    
                if ($existingAsset) {
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $existingAsset->id]));
                }
            }
            
            throw $e;
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Get current month and year from the form data or use current date
        $month = $data['month'] ?? now()->month;
        $year = $data['year'] ?? now()->year;
        
        // Get previous month's asset data
        $previousAsset = Asset::getPreviousMonthData(Auth::id(), $month, $year);
        
        if ($previousAsset) {
            // Pre-populate with previous month's data
            $previousData = $previousAsset->getFormDataForPrePopulation($data);
            
            // Merge the previous data with current form data
            // This allows users to override the pre-populated values
            $data = array_merge($previousData, $data);
            
            // Add a note to indicate data was pre-populated
            if (empty($data['notes'])) {
                $data['notes'] = "Pre-populated from {$previousAsset->formatted_period}";
            } else {
                $data['notes'] = "Pre-populated from {$previousAsset->formatted_period}. " . $data['notes'];
            }
        }
        
        return $data;
    }

    private function calculateAmounts(array &$data): void
    {
        // Calculate amounts for accounts
        if (isset($data['accounts'])) {
            foreach ($data['accounts'] as &$account) {
                if (isset($account['actual_amount']) && isset($account['exchange_rate']) && $account['exchange_rate'] > 0) {
                    $account['amount'] = (float) $account['actual_amount'] / (float) $account['exchange_rate'];
                }
            }
        }

        // Calculate amounts for lent money
        if (isset($data['lentMoney'])) {
            foreach ($data['lentMoney'] as &$lent) {
                if (isset($lent['actual_amount']) && isset($lent['exchange_rate']) && $lent['exchange_rate'] > 0) {
                    $lent['amount'] = (float) $lent['actual_amount'] / (float) $lent['exchange_rate'];
                }
            }
        }

        // Calculate amounts for borrowed money
        if (isset($data['borrowedMoney'])) {
            foreach ($data['borrowedMoney'] as &$borrowed) {
                if (isset($borrowed['actual_amount']) && isset($borrowed['exchange_rate']) && $borrowed['exchange_rate'] > 0) {
                    $borrowed['amount'] = (float) $borrowed['actual_amount'] / (float) $borrowed['exchange_rate'];
                }
            }
        }

        // Calculate amounts for investments
        if (isset($data['investments'])) {
            foreach ($data['investments'] as &$investment) {
                if (isset($investment['actual_amount']) && isset($investment['exchange_rate']) && $investment['exchange_rate'] > 0) {
                    $investment['amount'] = (float) $investment['actual_amount'] / (float) $investment['exchange_rate'];
                }
            }
        }

        // Calculate amounts for deposits
        if (isset($data['deposits'])) {
            foreach ($data['deposits'] as &$deposit) {
                if (isset($deposit['actual_amount']) && isset($deposit['exchange_rate']) && $deposit['exchange_rate'] > 0) {
                    $deposit['amount'] = (float) $deposit['actual_amount'] / (float) $deposit['exchange_rate'];
                }
            }
        }
    }
}
