<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Session;

class AssetsSecurity extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';
    
    protected string $view = 'filament.pages.assets-security';
    
    protected static bool $shouldRegisterNavigation = false;
    
    public ?array $data = [];
    
    public function mount(): void
    {
        // Check if user already has a valid session
        $securityCode = session('assets_security_code');
        $securityTimestamp = session('assets_security_timestamp');
        $isLocked = session('assets_security_locked', false);
        
        // If user has valid session and is not locked, redirect to assets
        if ($securityCode === '80313' && $securityTimestamp && (time() - $securityTimestamp) < 3600 && !$isLocked) {
            $this->redirect(route('filament.hisabat.resources.assets.index'));
        }
        
        $this->form->fill();
    }
    
    public function form($form)
    {
        return $form
            ->schema([
                TextInput::make('securityCode')
                    ->label('Security Code')
                    ->required()
                    ->string()
                    ->length(5)
                    ->numeric()
                    ->placeholder('00000')
                    ->extraInputAttributes(['class' => 'text-center text-lg tracking-widest'])
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        // Auto-submit when 5 digits are entered
                        if (strlen($state) === 5) {
                            $this->verifyCode();
                        }
                    }),
            ])
            ->statePath('data');
    }
    
    public function verifyCode(): void
    {
        $this->form->validate();
        
        if (($this->data['securityCode'] ?? '') === '80313') {
            // Set security session for 1 hour
            session([
                'assets_security_code' => '80313',
                'assets_security_timestamp' => time(),
                'assets_security_locked' => false
            ]);
            
            Notification::make()
                ->title('Security Verified')
                ->body('Access granted to assets.')
                ->success()
                ->send();
            
            // Redirect to assets page
            $this->redirect(route('filament.hisabat.resources.assets.index'));
        } else {
            Notification::make()
                ->title('Invalid Code')
                ->body('Please enter the correct 5-digit security code.')
                ->danger()
                ->send();
                
            $this->data['securityCode'] = '';
        }
    }
    
    public function getHeaderActions(): array
    {
        return [
            Action::make('verify')
                ->label('Verify Code')
                ->action('verifyCode')
                ->disabled(fn () => strlen($this->data['securityCode'] ?? '') !== 5)
                ->color('primary'),
        ];
    }
    
    public function getTitle(): string
    {
        return 'Assets Security';
    }
    
    public function getHeading(): string
    {
        return 'Security Required';
    }
    
    public function getSubheading(): ?string
    {
        return 'Enter the 5-digit security code to access assets';
    }
    
    public function goToDashboard(): void
    {
        $this->redirect(route('filament.hisabat.pages.dashboard'));
    }
}
