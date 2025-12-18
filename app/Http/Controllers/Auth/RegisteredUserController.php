<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Mail\NewAccountPendingMail;
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
            'account_status' => 'pending', // New accounts require admin approval
        ]);

        event(new Registered($user));

        // Don't send welcome email yet - send it when account is approved
        // Notify admin(s) about new pending registration
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new NewAccountPendingMail($user));
            }
        } catch (\Exception $e) {
            Log::error("Failed to send admin notification for new registration: " . $e->getMessage());
        }

        return redirect(route('login', absolute: false))->with('status', 'Registration successful! Your account is pending approval. You will receive an email once your account has been reviewed.');
    }
}
