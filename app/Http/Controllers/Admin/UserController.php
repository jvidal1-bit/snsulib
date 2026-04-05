<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $search = trim($request->input('q', ''));
        $role   = $request->input('role');
        $query  = User::with('profile');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if (!empty($role)) $query->where('role', $role);

        $users = $query->orderByDesc('created_at')->paginate(10)->withQueryString()
            ->through(fn($user) => [
                'id'           => $user->id,
                'display_name' => ($user->role === 'student' && $user->profile)
                    ? trim($user->profile->first_name . ' ' . $user->profile->last_name)
                    : $user->name,
                'username_note' => ($user->role === 'student' && $user->profile)
                    ? "(Username: {$user->name})" : null,
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => $user->role ?? 'admin',
                'status'     => $user->status ?? 'active',
                'course'     => $user->profile->course ?? '-',
                'year_level' => $user->profile->year_level ?? '-',
                'student_id' => $user->profile->student_id ?? null,
                'phone'      => $user->profile->phone ?? null,
                'address'    => $user->profile->address ?? null,
                'first_name' => $user->profile->first_name ?? null,
                'last_name'  => $user->profile->last_name ?? null,
            ]);

        return Inertia::render('Admin/Users', [
            'users'    => $users,
            'q'        => $search,
            'role'     => $role ?? '',
            'authName' => Auth::user()->name ?? 'Admin',
        ]);
    }

    public function edit(User $user)
    {
        $profile = $user->profile ?? new \App\Models\StudentProfile(['user_id' => $user->id]);

        return Inertia::render('Admin/UsersEdit', [
            'user' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => $user->role ?? 'admin',
                'status'     => $user->status ?? 'active',
                'student_id' => $profile->student_id,
                'first_name' => $profile->first_name,
                'last_name'  => $profile->last_name,
                'phone'      => $profile->phone,
                'course'     => $profile->course,
                'year_level' => $profile->year_level,
                'address'    => $profile->address,
            ],
            'authName' => Auth::user()->name ?? 'Admin',
        ]);
    }


    /**
     * Update name / email / role.
     */
    public function update(Request $request, User $user)
    {
        // 1) Validate all fields we send from the edit form
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role'       => ['required', 'in:admin,student'],
            'status'     => ['nullable', 'in:active,inactive'],

            // student profile fields (optional)
            'student_id' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name'  => ['nullable', 'string', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:50'],
            'course'     => ['nullable', 'string', 'max:255'],
            'year_level' => ['nullable', 'string', 'max:50'],
            'address'    => ['nullable', 'string'],
        ]);

        // 2) Update the user basic info
        $user->name   = $validated['name'];
        $user->email  = $validated['email'];
        $user->role   = $validated['role'];
        $user->status = $validated['status'] ?? $user->status ?? 'active';
        $user->save();

        // 3) Handle the student profile (only if role is student, but you can keep it even for admin)
        $profileData = [
            'student_id' => $validated['student_id'] ?? null,
            'first_name' => $validated['first_name'] ?? null,
            'last_name'  => $validated['last_name'] ?? null,
            'phone'      => $validated['phone'] ?? null,
            'course'     => $validated['course'] ?? null,
            'year_level' => $validated['year_level'] ?? null,
            'address'    => $validated['address'] ?? null,
        ];

        // Either get existing profile or create a new one
        $profile = $user->profile ?? new StudentProfile(['user_id' => $user->id]);
        $profile->fill($profileData);
        $profile->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Optional: delete user.
     */
    public function destroy(User $user)
    {
        // Optional protection: don’t let the last admin delete themselves, etc.
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete(); // or $user->forceDelete() / soft-delete if enabled

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
