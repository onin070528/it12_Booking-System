<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     * Prevents admin users from accessing regular user routes.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // If user is admin, redirect to admin dashboard
        if ($user->isAdmin()) {
            // Log unauthorized access attempt
            Log::info('Admin attempted to access user route', [
                'user_id' => $user->id,
                'email' => $user->email,
                'route' => $request->path(),
                'ip' => $request->ip(),
            ]);
            
            return redirect()->route('admin.dashboard')->with('error', 'Admins cannot access user routes. Please use the admin dashboard.');
        }

        // Ensure user has 'user' role
        if (!$user->isUser()) {
            Log::warning('Unauthorized user route access attempt', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'route' => $request->path(),
                'ip' => $request->ip(),
            ]);
            
            abort(403, 'Unauthorized access. User role required.');
        }

        // Double-check role value
        if ($user->role !== 'user') {
            Log::error('User role mismatch in UserMiddleware', [
                'user_id' => $user->id,
                'role' => $user->role,
            ]);
            abort(403, 'Invalid user role.');
        }

        return $next($request);
    }
}

