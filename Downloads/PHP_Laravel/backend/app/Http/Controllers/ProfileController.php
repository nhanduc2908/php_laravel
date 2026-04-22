<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        return $this->success(Auth::user(), 'Profile retrieved');
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $user->update($request->only(['name', 'email']));
        return $this->success($user, 'Profile updated');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|max:2048']);
        $path = $request->file('avatar')->store('avatars', 'public');
        Auth::user()->update(['avatar' => $path]);
        return $this->success(['path' => $path], 'Avatar uploaded');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed'
        ]);

        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->error('Current password is incorrect', 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->success(null, 'Password changed');
    }

    public function enable2FA()
    {
        $user = Auth::user();
        $secret = $user->generateTwoFactorSecret();
        return $this->success(['secret' => $secret], '2FA enabled');
    }

    public function disable2FA()
    {
        Auth::user()->disableTwoFactor();
        return $this->success(null, '2FA disabled');
    }

    public function sessions()
    {
        $sessions = Auth::user()->sessions;
        return $this->success($sessions, 'Active sessions');
    }
}