<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Update basic profile info for the logged-in admin.
     */
    public function update(Request $request)
{
    $user = auth()->user();

    $validated = $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'avatar' => 'nullable|image|max:2048', 
    ]);

    if ($request->hasFile('avatar')) {

        // delete old avatar
        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $validated['avatar_path'] = $path;
    }

    $user->update($validated);

    return back()->with('success', 'Account updated successfully.');
}



    /**
     * Update password for the logged-in admin.
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password'      => ['required'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Password updated successfully.');
    }
}
