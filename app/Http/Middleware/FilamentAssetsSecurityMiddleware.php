<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilamentAssetsSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip security check for the security page itself to avoid redirect loop
        if (str_contains($request->url(), '/assets-security') || !str_contains($request->url(), '/assets')) {
            return $next($request);
        }
        
        // Check if the user has valid assets security session
        $securityCode = session('assets_security_code');
        $securityTimestamp = session('assets_security_timestamp');
        $isLocked = session('assets_security_locked', true);
        
        // If locked, always require security code
        if ($isLocked) {
            return $this->requireSecurityCode($request);
        }
        
        // Check if security code is valid and not expired (1 hour = 3600 seconds)
        if ($securityCode === '80313' && $securityTimestamp && (time() - $securityTimestamp) < 3600) {
            $response = $next($request);
            
            // Add cache prevention headers to asset pages
            if (str_contains($request->url(), '/assets')) {
                $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
                $response->headers->set('Pragma', 'no-cache');
                $response->headers->set('Expires', '0');
            }
            
            return $response;
        }
        
        // Require security code for assets-related routes
        if (str_contains($request->url(), '/assets')) {
            return $this->requireSecurityCode($request);
        }

        return $next($request);
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
        
        // Redirect to Filament security page with cache prevention headers
        return redirect()->route('filament.hisabat.pages.assets-security')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
