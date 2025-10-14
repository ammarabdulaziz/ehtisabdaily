<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class GlobalSecurityController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('GlobalSecurity');
    }
    
    public function status(): JsonResponse
    {
        $isLocked = session('global_security_locked', false);
        $hasValidCode = $this->hasValidSecurityCode();
        
        return response()->json([
            'is_locked' => $isLocked,
            'has_valid_code' => $hasValidCode,
            'is_accessible' => !$isLocked || $hasValidCode
        ]);
    }
    
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'security_code' => 'required|string|size:5'
        ]);
        
        if ($request->security_code === '80313') {
            // Set security session for 1 hour
            session([
                'global_security_code' => '80313',
                'global_security_timestamp' => time(),
                'global_security_locked' => false
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Security code verified successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid security code'
        ], 422);
    }
    
    public function toggleLock(): JsonResponse
    {
        $isLocked = !session('global_security_locked', false);
        session(['global_security_locked' => $isLocked]);
        
        if ($isLocked) {
            // Clear security session when locked
            session()->forget(['global_security_code', 'global_security_timestamp']);
        }
        
        return response()->json([
            'success' => true,
            'is_locked' => $isLocked,
            'message' => $isLocked ? 'Security locked' : 'Security unlocked'
        ]);
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
