<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function store(LoginRequest $request): RedirectResponse
    {
        // First check if the user exists and their account status
        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            // Check account status before authentication
            if ($user->account_status === 'pending') {
                return back()->withErrors([
                    'email' => 'Your account is pending approval. Please wait for an administrator to review your registration.',
                ])->withInput($request->only('email'));
            }
            
            if ($user->account_status === 'rejected') {
                return back()->withErrors([
                    'email' => 'Your account registration was not approved. Please contact support for more information.',
                ])->withInput($request->only('email'));
            }
        }
        
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect based on user role
        /** @var User $user */
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
