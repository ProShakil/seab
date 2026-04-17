<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */


    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $request->file('profile_image')->store('profiles', 'public');
        }

        if ($request->hasFile('signature_image')) {
            if ($user->signature) {
                Storage::disk('public')->delete($user->signature);
            }

            $user->signature = $request->file('signature_image')->store('signatures', 'public');
        }

        $user->save();

        return redirect()->route('user.profile');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function view() {
        return Inertia::render('Users/Index', [
            'user' => auth()->user()->load(['relationship', 'technology','occupation']),
        ]);
    }
    public function membership_form()
    {
        return Inertia::render('Users/ProfileEdit', [
            'user' => auth()->user(),
            'relationships' => \App\Models\Relationship::select('id','name')->get(),
            'occupations' => \App\Models\Occupation::select('id','name')->get(),
            'technologies' => \App\Models\Technology::select('id','name')->get(),
        ]);
    }

    public function update_password() {
        return Inertia::render('Users/UpdatePassword', [
            'user' => auth()->user()
        ]);
    }

    
}
