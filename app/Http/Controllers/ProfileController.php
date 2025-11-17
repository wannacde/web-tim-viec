<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Validated data (Name, Email, Phone)
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Xử lý riêng cho Employer
        if ($user->role === 'employer') {
            $request->validate([
                'company_name' => 'required|string|max:255',
                'company_website' => 'nullable|url|max:255',
                'company_address' => 'nullable|string|max:255',
                'company_description' => 'nullable|string|max:2000',
                'company_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            ]);

            $user->company_name = $request->input('company_name');
            $user->company_website = $request->input('company_website');
            $user->company_address = $request->input('company_address');
            $user->company_description = $request->input('company_description');

            if ($request->hasFile('company_logo')) {
                // Xóa logo cũ nếu có
                if ($user->company_logo) {
                    Storage::disk('public')->delete($user->company_logo);
                }
                // Lưu logo mới
                $path = $request->file('company_logo')->store('logos', 'public');
                $user->company_logo = $path;
            }
        }

        // Xử lý riêng cho Student
        if ($user->role === 'student') {
            $request->validate([
                'headline' => 'nullable|string|max:255',
                'bio' => 'nullable|string|max:1000',
            ]);

            $user->headline = $request->headline;
            $user->bio = $request->bio;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
