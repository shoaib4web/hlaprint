<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        // Attempt to authenticate the user using the request data
        $request->authenticate();

        // Regenerate session for security in case of web request
        $request->session()->regenerate();

        // Check if the request is an API call
        if ($request->wantsJson()) {
            // Generate a Sanctum token for API access
            $user = Auth::user();
            $token = $user->createToken('access-token')->plainTextToken;

            return response()->json([
                'message' => 'Credentials Validated',
                'token' => $token,
            ]);
        }

        // Default response for web-based login
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Handle an incoming API authentication request and return a JSON response with a token.
     */
    public function api_store(LoginRequest $request): JsonResponse
    {
        // Attempt to authenticate the user
        $request->authenticate();

        // Regenerate session to prevent session fixation attacks
        $request->session()->regenerate();

        // Generate a Sanctum token for API access
        $user = Auth::user();
        $token = $user->createToken('api-access-token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'token' => $token,
        ], 200);
    }

    /**
     * Destroy an API session and revoke the token.
     */
    public function api_destroy(Request $request): JsonResponse
    {
        // Revoke all tokens for the authenticated user
        $request->user()->tokens()->delete();

        // Invalidate and regenerate the session token for additional security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        // Check if the request is an API call
        if ($request->wantsJson()) {
            // Revoke all tokens for the authenticated user
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logged out successfully',
            ]);
        }

        // For web-based logout, use the session-based logout
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
