<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Helper to verify if the user is a Developer or Admin.
     */
    protected function authorizeCreator()
    {
        $currentUser = Auth::user();
        if (!$currentUser || !in_array($currentUser->users_level, [0, 1])) {
            abort(403, 'Unauthorized. Only developers and school admins can access this page.');
        }
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $this->authorizeCreator();
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorizeCreator();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'mobile_number' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'users_level' => ['required', 'integer', 'in:1,2,3,4'],
            'avatar_file' => ['nullable', 'image', 'max:5120'],
            'avatar_base64' => ['nullable', 'string'],
        ]);

        $currentUser = Auth::user();
        $targetLevel = (int) $request->users_level;

        if ($currentUser->isSchoolAdmin()) {
            if ($targetLevel === 1) {
                return back()->withErrors(['users_level' => 'Admins can only create Teacher, Student, and Parent accounts.'])->withInput();
            }
        }

        $avatarPath = null;
        if ($request->filled('avatar_base64')) {
            $data = $request->avatar_base64;
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]);
                if (in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $data = base64_decode($data);
                    if ($data !== false) {
                        $filename = 'avatar_' . uniqid() . '.' . $type;
                        \Illuminate\Support\Facades\Storage::disk('public')->put('avatars/' . $filename, $data);
                        $avatarPath = 'avatars/' . $filename;
                    }
                }
            }
        } elseif ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $filename = 'avatar_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $avatarPath = $file->storeAs('avatars', $filename, 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
            'users_level' => $targetLevel,
            'can_delete_users' => $request->has('can_delete_users') && $targetLevel === 2,
            'avatar' => $avatarPath,
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => $currentUser->id,
            'user_name' => $currentUser->name,
            'user_email' => $currentUser->email,
            'action' => 'Create User',
            'description' => "Created user {$user->name} with role {$user->role_name}.",
            'ip_address' => $request->ip(),
        ]);

        event(new Registered($user));

        return redirect(route('users.index'))->with('success', "User {$user->name} created successfully.");
    }
}
