<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGlobalSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isLocked = session('global_security_locked', false);
        
        if ($isLocked) {
            $hasValidCode = $this->hasValidSecurityCode();
            
            if (!$hasValidCode) {
                // Store intended URL for redirect after verification
                session(['url.intended' => $request->fullUrl()]);
                
                // Redirect to global security verification page
                return redirect()->route('global-security.show');
            }
        }
        
        return $next($request);
    }
    
    private function hasValidSecurityCode(): bool
    {
        $code = session('global_security_code');
        $timestamp = session('global_security_timestamp');
        
        if (!$code || !$timestamp) {
            return false;
        }
        
        // Check if code is still valid (1 hour = 3600 seconds)
        if (time() - $timestamp > 3600) {
            // Clear expired session
            session()->forget(['global_security_code', 'global_security_timestamp']);
            return false;
        }
        
        return $code === '80313';
    }
}
