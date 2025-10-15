<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LinkPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class LinkPasswordController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('auth/link-password');
    }

    public function store(LinkPasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();
        
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('dashboard')->with('success', 'Password linked successfully!');
    }

    public function skip(): RedirectResponse
    {
        return redirect()->route('dashboard')->with('info', 'You can link a password later in your account settings.');
    }
}