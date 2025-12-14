<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Return the currently authenticated user (or null).
     *
     * @return User|null
     */
    protected function authUser(): ?User
    {
        return Auth::user();
    }

    /**
     * Convenience helper to check if the current user is an admin.
     */
    protected function isAdmin(): bool
    {
        $user = $this->authUser();
        return $user ? $user->isAdmin() : false;
    }
}
