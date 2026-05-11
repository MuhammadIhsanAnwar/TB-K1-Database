<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Seller;
use App\Models\Review;
use App\Models\User;
use App\Notifications\AccountDeletionVerification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function profile(): View
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('settings.index', [
            'user' => $user,
            'profile' => $profile,
            'selectedTab' => 'profile',
        ]);
    }

    public function account(): View
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('settings.index', [
            'user' => $user,
            'profile' => $profile,
            'selectedTab' => 'account',
        ]);
    }

    public function buyer(): View
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('settings.index', [
            'user' => $user,
            'profile' => $profile,
            'selectedTab' => 'buyer',
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', 'in:male,female,other'],
            'birth_date' => ['nullable', 'date', 'before:today'],
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
            ]
        );

        return back()->with('success', 'Pengaturan profil berhasil diperbarui.');
    }

    public function sendDeletionCode(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $user->forceFill([
            'account_deletion_token' => Hash::make($code),
            'account_deletion_token_sent_at' => now(),
        ])->save();

        $user->notify(new AccountDeletionVerification($code));

        return back()->with('status', 'Kode verifikasi telah dikirim ke email Anda. Silakan periksa kotak masuk.');
    }

    public function confirmDeletionForm(): View
    {
        $user = Auth::user();

        return view('settings.delete-account', [
            'user' => $user,
        ]);
    }

    public function reactivateForm(): View
    {
        $user = Auth::user();

        if (!$user || !$user->trashed() || !$user->deactivated_at) {
            return redirect()->route('home');
        }

        return view('auth.reactivate-account', [
            'user' => $user,
        ]);
    }
}
