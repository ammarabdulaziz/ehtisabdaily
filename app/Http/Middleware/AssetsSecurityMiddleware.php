<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssetsSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user has valid assets security session
        $securityCode = session('assets_security_code');
        $securityTimestamp = session('assets_security_timestamp');
        $isLocked = session('assets_security_locked', false);
        
        // If locked, always require security code
        if ($isLocked) {
            return $this->requireSecurityCode($request);
        }
        
        // Check if security code is valid and not expired (1 hour = 3600 seconds)
        if ($securityCode === '80313' && $securityTimestamp && (time() - $securityTimestamp) < 3600) {
            return $next($request);
        }
        
        // Require security code
        return $this->requireSecurityCode($request);
    }
    
    private function requireSecurityCode(Request $request): Response
    {
        // For AJAX requests, return JSON response
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'error' => 'Security code required',
                'requires_security_code' => true
            ], 403);
        }
        
        // For regular requests, redirect to security page
        // Check if this is a Filament request
        if (str_contains($request->url(), '/hisabat/assets')) {
            return redirect()->route('filament.hisabat.pages.assets-security');
        }
        
        return redirect()->route('assets.security');
    }
}
