<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Google\Client;
use Google\Service\Oauth2;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect_uri'));
        $client->setScopes(config('services.google.scopes'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        $authUrl = $client->createAuthUrl();

        return redirect($authUrl);
    }

    public function callback(Request $request): RedirectResponse
    {
        if ($request->has('error')) {
            // If user is authenticated, redirect to YouTube page, otherwise to login
            if (Auth::check()) {
                return redirect()->route('youtube.index')->with('error', 'Google authentication was cancelled.');
            }
            return redirect()->route('login')->with('error', 'Google authentication was cancelled.');
        }

        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect_uri'));

        try {
            $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));
            
            if (isset($token['error'])) {
                if (Auth::check()) {
                    return redirect()->route('youtube.index')->with('error', 'Failed to authenticate with Google.');
                }
                return redirect()->route('login')->with('error', 'Failed to authenticate with Google.');
            }

            $client->setAccessToken($token);

            // Get user info from Google
            $oauth2 = new Oauth2($client);
            $userInfo = $oauth2->userinfo->get();

            $googleId = $userInfo->getId();
            $email = $userInfo->getEmail();
            $name = $userInfo->getName();
            $avatar = $userInfo->getPicture();

            // If user is already authenticated, link the Google account
            if (Auth::check()) {
                $user = Auth::user();
                
                // Check if another user already has this Google ID
                $existingGoogleUser = User::where('google_id', $googleId)->where('id', '!=', $user->id)->first();
                if ($existingGoogleUser) {
                    return redirect()->route('youtube.index')->with('error', 'This Google account is already linked to another user.');
                }

                // Update the current user with Google account info
                $user->update([
                    'google_id' => $googleId,
                    'google_access_token' => $token['access_token'],
                    'google_refresh_token' => $token['refresh_token'] ?? $user->google_refresh_token,
                    'avatar' => $avatar,
                ]);

                return redirect()->route('youtube.index')->with('success', 'Google account linked successfully!');
            }

            // Handle guest user authentication
            // Check if user exists by Google ID
            $user = User::where('google_id', $googleId)->first();

            if (!$user) {
                // Check if user exists by email
                $user = User::where('email', $email)->first();

                if ($user) {
                    // Link existing user with Google account
                    $user->update([
                        'google_id' => $googleId,
                        'google_access_token' => $token['access_token'],
                        'google_refresh_token' => $token['refresh_token'] ?? null,
                        'avatar' => $avatar,
                    ]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'google_id' => $googleId,
                        'google_access_token' => $token['access_token'],
                        'google_refresh_token' => $token['refresh_token'] ?? null,
                        'avatar' => $avatar,
                        'password' => null, // No password for Google-only users
                    ]);
                }
            } else {
                // Update existing Google user's tokens
                $user->update([
                    'google_access_token' => $token['access_token'],
                    'google_refresh_token' => $token['refresh_token'] ?? $user->google_refresh_token,
                ]);
            }

            // Log the user in
            Auth::login($user);

            // Check if user needs to link a password
            if (!$user->password) {
                return redirect()->route('auth.link-password');
            }

            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            if (Auth::check()) {
                return redirect()->route('youtube.index')->with('error', 'Failed to link Google account. Please try again.');
            }
            return redirect()->route('login')->with('error', 'Authentication failed. Please try again.');
        }
    }

}