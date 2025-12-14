<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Ensures only users with 'admin' role can access admin routes.
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

        // Ensure user has admin role
        if (!$user->isAdmin()) {
            // Log unauthorized access attempt
            Log::warning('Unauthorized admin access attempt', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'route' => $request->path(),
                'ip' => $request->ip(),
            ]);
            
            abort(403, 'Unauthorized access. Admin role required.');
        }

        // Double-check role value
        if ($user->role !== 'admin') {
            Log::error('User role mismatch in AdminMiddleware', [
                'user_id' => $user->id,
                'role' => $user->role,
            ]);
            abort(403, 'Invalid user role.');
        }

        return $next($request);
    }
}
