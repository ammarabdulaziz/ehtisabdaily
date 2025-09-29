<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class AssetsSecurityController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Assets/Security');
    }
    
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'security_code' => 'required|string|size:5'
        ]);
        
        if ($request->security_code === '80313') {
            // Set security session for 1 hour
            session([
                'assets_security_code' => '80313',
                'assets_security_timestamp' => time(),
                'assets_security_locked' => false
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
        $isLocked = !session('assets_security_locked', false);
        session(['assets_security_locked' => $isLocked]);
        
        if ($isLocked) {
            // Clear security session when locked
            session()->forget(['assets_security_code', 'assets_security_timestamp']);
        }
        
        return response()->json([
            'success' => true,
            'is_locked' => $isLocked,
            'message' => $isLocked ? 'Security locked' : 'Security unlocked'
        ]);
    }
}
