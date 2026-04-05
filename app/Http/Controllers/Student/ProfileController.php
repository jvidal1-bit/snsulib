<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Get the currently authenticated user.
     */
    protected function currentUser(): User
    {
        return Auth::user();
    }

    /**
     * Show profile page.
     */
    public function edit()
    {
        $user    = Auth::user();
        $profile = $user->profile ?? new StudentProfile(['user_id' => $user->id]);

        return Inertia::render('Student/Profile', [
            'profile' => [
                'student_id' => $profile->student_id,
                'email'      => $user->email,
                'first_name' => $profile->first_name,
                'last_name'  => $profile->last_name,
                'phone'      => $profile->phone,
                'course'     => $profile->course,
                'year_level' => $profile->year_level,
                'address'    => $profile->address,
                'avatar_url' => $user->avatar_path
                    ? asset('storage/' . $user->avatar_path)
                    : null,
            ],
            'authName' => $user->name ?? 'Student',
        ]);
    }

    /**
     * Update profile info & picture.
     */
    public function update(Request $request)
{
    $user = $this->currentUser();

    $validated = $request->validate([
        'student_id'      => ['nullable', 'string', 'max:255'],
        'first_name'      => ['required', 'string', 'max:255'],
        'last_name'       => ['required', 'string', 'max:255'],
        'email'           => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'phone'           => ['nullable', 'string', 'max:50'],
        'course'          => ['nullable', 'string', 'max:255'],
        'year_level'      => ['nullable', 'string', 'max:50'],
        'address'         => ['nullable', 'string'],
        'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    // USERS TABLE
    $user->email = $validated['email'];
    $user->name  = trim($validated['first_name'] . ' ' . $validated['last_name']);

    if ($request->hasFile('profile_picture')) {
        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        $user->avatar_path = $path;
    }

    $user->save();

    // STUDENT_PROFILES TABLE
    $profileData = $validated;
    unset($profileData['email'], $profileData['profile_picture']);

    $profile = $user->profile ?? new StudentProfile(['user_id' => $user->id]);

    if (isset($path)) {
        $profileData['profile_picture'] = $path;
    }

    $profile->fill($profileData);
    $profile->save();

    return back()->with('status', 'Profile updated successfully.');
}


    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $user = $this->currentUser();

        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password'     => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return back()->with('status', 'Password changed successfully.');
    }
}
