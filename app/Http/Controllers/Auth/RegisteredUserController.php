<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Services\EmailNotificationService;
use App\Services\EmailValidationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, EmailValidationService $emailValidationService): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_initial' => ['nullable', 'string', 'max:1'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'regex:/^[0-9]{4}-[0-9]{3}-[0-9]{4}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Basic email format validation (don't block on DNS checks - they can fail for valid emails)
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return back()
                ->withInput($request->only('email', 'first_name', 'last_name', 'middle_initial'))
                ->withErrors(['email' => 'The email address format is invalid.']);
        }

        // Build full name from components
        $fullName = trim($request->first_name . ' ' . 
                       ($request->middle_initial ? strtoupper($request->middle_initial) . '. ' : '') . 
                       $request->last_name);

        $user = User::create([
            'name' => $fullName,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_initial ? strtoupper($request->middle_initial) : null,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password, // The User model's 'hashed' cast will automatically hash this
        ]);

        event(new Registered($user));

        // Send welcome email
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Exception $e) {
            Log::error("Failed to send welcome email to {$user->email}: " . $e->getMessage());
            // Don't fail registration if email fails
        }

        return redirect(route('login', absolute: false))->with('status', 'Registration successful! Please check your email for a welcome message.');
    }
}
