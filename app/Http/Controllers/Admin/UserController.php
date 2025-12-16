<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('q', ''));
        $role   = $request->input('role');
        $query = User::query();
        $query = User::with('profile');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }
        if (!empty($role)) {
        $query->where('role', $role);
    }
             $users = $query
        ->orderBy('name')
        ->paginate(15)
        ->withQueryString();

        $users = $query->orderByDesc('created_at')
                       ->paginate(10)
                       ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Show edit form.
     */
    public function edit(User $user)
{
    $profile = $user->profile ?? new \App\Models\StudentProfile([
        'user_id' => $user->id,
    ]);

    return view('admin.users.edit', compact('user', 'profile'));
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
