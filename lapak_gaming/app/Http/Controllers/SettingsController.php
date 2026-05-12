<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Seller;
use App\Models\Review;
use App\Models\User;
use App\Notifications\AccountDeletionVerification;
use App\Notifications\PasswordChangeVerification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function profile(): View
    {
        /** @var User $user */
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
        /** @var User $user */
        $user = Auth::user();
        $profile = $user->profile;

        return view('settings.index', [
            'user' => $user,
            'profile' => $profile,
            'selectedTab' => 'account',
        ]);
    }

    public function password(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $profile = $user->profile;

        return view('settings.index', [
            'user' => $user,
            'profile' => $profile,
            'selectedTab' => 'password',
        ]);
    }

    public function seller(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $profile = $user->profile;

        return view('settings.index', [
            'user' => $user,
            'profile' => $profile,
            'selectedTab' => 'seller',
        ]);
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
        /** @var User $user */
        $user = Auth::user();
        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $user->forceFill([
            'account_deletion_token' => Hash::make($code),
            'account_deletion_token_sent_at' => now(),
        ])->save();

        $user->notify(new AccountDeletionVerification($code));

        return back()->with('status', 'Kode verifikasi telah dikirim ke email Anda. Silakan periksa kotak masuk.');
    }

    public function sendPasswordChangeCode(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put($this->passwordChangeCodeKey($user), Hash::make($code), now()->addMinutes(10));

        $user->notify(new PasswordChangeVerification($code));

        return back()->with('status', 'Kode verifikasi ubah password telah dikirim ke email Anda.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $data = $request->validate([
            'verification_code' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $storedCode = Cache::get($this->passwordChangeCodeKey($user));

        if (! $storedCode) {
            return back()->withErrors([
                'verification_code' => 'Kode verifikasi belum dikirim atau sudah kedaluwarsa.',
            ]);
        }

        if (! Hash::check($data['verification_code'], $storedCode)) {
            return back()->withErrors([
                'verification_code' => 'Kode verifikasi tidak valid.',
            ]);
        }

        $user->forceFill([
            'password' => $data['password'],
        ])->save();

        Cache::forget($this->passwordChangeCodeKey($user));

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    private function passwordChangeCodeKey(User $user): string
    {
        return 'password-change-code:' . $user->id;
    }

    public function confirmDeletionForm(): View
    {
        /** @var User $user */
        $user = Auth::user();

        return view('settings.delete-account', [
            'user' => $user,
        ]);
    }

    public function reactivateForm(): View|RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user || ! $user->deactivated_at) {
            return redirect()->route('home');
        }

        return view('auth.reactivate-account', [
            'user' => $user,
        ]);
    }
}
