<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $profile = $user->profile;

        return view('settings.index', compact('user', 'profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', 'in:male,female,other'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $avatarPath = $user->avatar;
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $avatarFilename = Str::uuid()->toString() . '.' . $photo->getClientOriginalExtension();
            Storage::disk('public_app_public')->putFileAs('user-avatars', $photo, $avatarFilename);
            $avatarPath = 'app/public/user-avatars/' . $avatarFilename;
        }

        $user->update([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? $user->phone,
            'avatar' => $avatarPath,
        ]);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'gender' => $data['gender'] ?? ($user->profile?->gender ?? 'other'),
                'birth_date' => $data['birth_date'] ?? $user->profile?->birth_date,
                'phone' => $data['phone'] ?? $user->profile?->phone ?? '-',
                'avatar_path' => $avatarPath,
                'bio' => $data['bio'] ?? $user->profile?->bio,
            ]
        );

        return back()->with('success', 'Pengaturan profil berhasil diperbarui.');
    }
}
