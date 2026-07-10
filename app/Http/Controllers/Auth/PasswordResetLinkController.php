<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(Request $request): View
    {
        if (session()->has('reset_user_id')) {
            $user = User::find(session('reset_user_id'));
            if ($user) {
                return view('auth.forgot-password', [
                    'user' => $user,
                    'verified' => true
                ]);
            }
        }

        return view('auth.forgot-password', [
            'verified' => false
        ]);
    }

    /**
     * Handle identity verification or update user credentials directly.
     */
    public function store(Request $request): RedirectResponse
    {
        // Check if user requested to reset/cancel the recovery flow
        if ($request->has('reset_flow')) {
            session()->forget('reset_user_id');
            return redirect()->route('password.request');
        }

        // If updating details:
        if ($request->has('update_credentials')) {
            if (!session()->has('reset_user_id')) {
                return redirect()->route('password.request')->withErrors(['identity' => 'Session expired. Please verify again.']);
            }

            $user = User::findOrFail(session('reset_user_id'));

            $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
                'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'mobile_number' => ['nullable', 'string', 'max:255'],
                'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            ]);

            $user->username = $request->username;
            $user->email = $request->email;
            $user->mobile_number = $request->mobile_number;
            
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            $user->save();

            // Clear the session
            session()->forget('reset_user_id');

            return redirect()->route('login')->with('status', 'Your credentials have been updated successfully. You can now log in.');
        }

        // Otherwise, verify identity:
        $request->validate([
            'name' => ['required', 'string'],
            'email_or_mobile' => ['required', 'string'],
        ]);

        $user = User::where('name', $request->name)
            ->where(function ($query) use ($request) {
                $query->where('email', $request->email_or_mobile)
                      ->orWhere('mobile_number', $request->email_or_mobile);
            })
            ->first();

        if (!$user) {
            return back()->withInput()->withErrors([
                'identity' => 'The provided details do not match our records.'
            ]);
        }

        session(['reset_user_id' => $user->id]);

        return redirect()->route('password.request');
    }
}
