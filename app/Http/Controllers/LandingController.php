<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\LandingContactRequest;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing page.landing');
    }

    public function submit(LandingContactRequest $request): RedirectResponse
    {
        // Example: Mail::to(config('mail.from.address'))->queue(new ContactMail($request->validated()));
        
        return back()->with('success', 'Thank you! We will contact you soon.');
    }
}
