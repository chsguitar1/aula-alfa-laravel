<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function showResetForm( )
    {
        return view('auth.forgot-password');
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
           
            'email' => 'required|email',
           
        ]);

    }
}