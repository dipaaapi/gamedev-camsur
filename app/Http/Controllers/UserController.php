<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        if (!$currentUser || !in_array($currentUser->users_level, [0, 1, 2])) {
            abort(403, 'Unauthorized access to user management.');
        }

        if ($currentUser->isTeacher()) {
            // Teacher can only view Students (3) and Parents (4)
            $users = User::whereIn('users_level', [3, 4])
                ->orderBy('name')
                ->paginate(20);
        } else {
            // Developer and Admin can view everyone
            $users = User::orderBy('users_level', 'asc')
                ->orderBy('name')
                ->paginate(20);
        }

        return view('users.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser || !in_array($currentUser->users_level, [0, 1, 2])) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'mobile_number' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
            'users_level' => ['required', 'integer', 'in:0,1,2,3,4'],
        ]);

        $newLevel = (int) $request->users_level;

        if ($user->id === $currentUser->id) {
            abort(403, 'You cannot change your own user level.');
        }

        // Developer level (0): can edit user levels 1, 2, 3, and 4. They cannot edit level 0.
        if ($currentUser->isDeveloper()) {
            if ($user->users_level === 0 || $newLevel === 0) {
                abort(403, 'Developers cannot change Developer level accounts.');
            }
        }

        // Admin level (1): can edit user levels 2, 3, and 4 only. They cannot edit level 0 or 1.
        if ($currentUser->isSchoolAdmin()) {
            if (in_array($user->users_level, [0, 1]) || in_array($newLevel, [0, 1])) {
                abort(403, 'Admins can only edit user levels 2, 3, and 4.');
            }
        }

        // Teacher logic: can only edit student/parent and can only assign student/parent
        if ($currentUser->isTeacher()) {
            if (!in_array($user->users_level, [3, 4]) || !in_array($newLevel, [3, 4])) {
                abort(403, 'Teachers can only manage Student and Parent/Guardian levels.');
            }
        }

        $oldRoleName = $user->role_name;
        
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->mobile_number = $validated['mobile_number'];
        $user->users_level = $newLevel;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($newLevel === 2) {
            $user->can_delete_users = $request->has('can_delete_users');
        } else {
            $user->can_delete_users = false;
        }

        $user->save();

        ActivityLog::create([
            'user_id' => $currentUser->id,
            'user_name' => $currentUser->name,
            'user_email' => $currentUser->email,
            'action' => 'Update User Details',
            'description' => "Updated details and level for user {$user->name} ({$user->role_name}).",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.index')->with('success', "Updated {$user->name}'s account details successfully.");
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();
        if (!$currentUser || !in_array($currentUser->users_level, [0, 1])) {
            abort(403, 'Only developers and school admins can create user accounts.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'mobile_number' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'users_level' => ['required', 'integer', 'in:1,2,3,4'],
        ]);

        if ($currentUser->isSchoolAdmin()) {
            if ($validated['users_level'] === 1) {
                return back()->withErrors(['users_level' => 'Admins can only create Teacher, Student, and Parent accounts.'])->withInput();
            }
        }

        $validated['password'] = bcrypt($validated['password']);
        $validated['can_delete_users'] = $request->has('can_delete_users') && (int) $validated['users_level'] === 2;

        $user = User::create($validated);

        ActivityLog::create([
            'user_id' => $currentUser->id,
            'user_name' => $currentUser->name,
            'user_email' => $currentUser->email,
            'action' => 'Create User',
            'description' => "Created user {$user->name} with role {$user->role_name}.",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.index')->with('success', "User {$user->name} created successfully.");
    }

    public function destroy(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser || !$currentUser->canDeleteOtherUsers()) {
            abort(403, 'Unauthorized.');
        }

        if ($user->id === $currentUser->id) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        // Developer (0) can delete any account except other Developers.
        if ($currentUser->isDeveloper()) {
            if ($user->users_level === 0) {
                abort(403, 'Developers cannot delete other Developer accounts.');
            }
        }

        // Admin (1) can delete Teachers, Students, Parents. Cannot delete Developer or Admin.
        if ($currentUser->isSchoolAdmin()) {
            if (in_array($user->users_level, [0, 1])) {
                abort(403, 'Admins cannot delete Developer or Admin accounts.');
            }
        }

        // Teacher (2) (if granted) can delete Students and Parents.
        if ($currentUser->isTeacher()) {
            if (!in_array($user->users_level, [3, 4])) {
                abort(403, 'Teachers can only delete Student or Parent accounts.');
            }
        }

        $deletedName = $user->name;
        $deletedRole = $user->role_name;

        $user->delete();

        ActivityLog::create([
            'user_id' => $currentUser->id,
            'user_name' => $currentUser->name,
            'user_email' => $currentUser->email,
            'action' => 'Delete User',
            'description' => "Deleted user {$deletedName} ({$deletedRole}).",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.index')->with('success', "User {$deletedName} deleted successfully.");
    }

    public function edit(User $user)
    {
        $currentUser = Auth::user();
        if (!$currentUser || !in_array($currentUser->users_level, [0, 1, 2])) {
            abort(403, 'Unauthorized.');
        }

        $canEdit = false;
        if ($user->id !== $currentUser->id) {
            if ($currentUser->isDeveloper() && in_array($user->users_level, [1, 2, 3, 4])) {
                $canEdit = true;
            } elseif ($currentUser->isSchoolAdmin() && in_array($user->users_level, [2, 3, 4])) {
                $canEdit = true;
            } elseif ($currentUser->isTeacher() && in_array($user->users_level, [3, 4])) {
                $canEdit = true;
            }
        }

        if (!$canEdit) {
            abort(403, 'You do not have permission to edit this user.');
        }

        return view('auth.register', compact('user'));
    }
}
