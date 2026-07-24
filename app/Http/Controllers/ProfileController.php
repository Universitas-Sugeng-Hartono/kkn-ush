<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

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
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Handle photo upload if present
        if ($request->hasFile('photo')) {
            try {
                $user = $request->user();

                // Hapus foto lama jika ada
                if ($user->photo) {
                    Storage::disk('public')->delete($user->photo);
                }

                // Upload foto baru
                $path = $request->file('photo')->store('profile-photos', 'public');
                
                // Update user photo
                $user->photo = $path;
            } catch (\Exception $e) {
                \Log::error('Photo upload error in profile update: ' . $e->getMessage());
            }
        }

        $request->user()->save();

        // Check if request is from mobile
        $isMobile = session('is_mobile_device', false);
        
        if ($isMobile) {
            return Redirect::route('mobile.profile.edit')->with('status', 'profile-updated');
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        try {
        $request->validate([
            'photo' => ['required', 'image', 'max:2048'] // max 2MB
        ]);

        $user = $request->user();

        // Hapus foto lama jika ada
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        // Upload foto baru
        $path = $request->file('photo')->store('profile-photos', 'public');
        
        // Update user
        $user->update([
            'photo' => $path
        ]);

            // Check if request is from mobile
            $isMobile = session('is_mobile_device', false);
            
            if ($isMobile) {
                return Redirect::route('mobile.profile.edit')->with('status', 'photo-updated');
            }

        return Redirect::route('profile.edit')->with('status', 'photo-updated');
        } catch (\Exception $e) {
            \Log::error('Photo upload error: ' . $e->getMessage());
            
            // Check if request is from mobile
            $isMobile = session('is_mobile_device', false);
            
            if ($isMobile) {
                return Redirect::route('mobile.profile.edit')->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
            }

            return Redirect::route('profile.edit')->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
        }
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

        // Hapus foto profil jika ada
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        // Hapus FCM Tokens
        $user->fcmTokens()->delete();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
