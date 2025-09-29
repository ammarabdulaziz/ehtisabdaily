<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;

class AssetsSecurityModal extends Component
{
    #[Validate('required|string|size:5')]
    public string $securityCode = '';
    
    public bool $isLocked = false;
    
    public function mount(): void
    {
        $this->isLocked = session('assets_security_locked', false);
    }
    
    public function verifyCode(): void
    {
        $this->validate();
        
        if ($this->securityCode === '80313') {
            // Set security session for 1 hour
            session([
                'assets_security_code' => '80313',
                'assets_security_timestamp' => time(),
                'assets_security_locked' => false
            ]);
            
            $this->dispatch('security-verified');
            
            // Redirect to the intended URL or assets page
            $intendedUrl = session('url.intended', route('assets.index'));
            return redirect($intendedUrl);
        }
        
        $this->addError('securityCode', 'Invalid security code');
    }
    
    public function toggleLock(): void
    {
        $this->isLocked = !$this->isLocked;
        session(['assets_security_locked' => $this->isLocked]);
        
        if ($this->isLocked) {
            // Clear security session when locked
            session()->forget(['assets_security_code', 'assets_security_timestamp']);
        }
    }
    
    public function render()
    {
        return view('livewire.assets-security-modal');
    }
}
