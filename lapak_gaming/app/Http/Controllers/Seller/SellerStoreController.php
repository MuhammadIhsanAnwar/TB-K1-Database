<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class SellerStoreController extends Controller
{
    public function edit()
    {
        /** @var User|null $user */
        $user = Auth::user();
        $profile = $user?->profile;

        return view('seller.store', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $profile = $user->profile;

        $messages = [
            'store_name.required' => 'Nama toko wajib diisi.',
            'store_name.max' => 'Nama toko maksimal 255 karakter.',
            'store_photo.image' => 'Foto toko harus berupa gambar.',
            'store_photo.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WebP.',
            'store_photo.max' => 'Ukuran foto maksimal 5MB.',
            'bio.max' => 'Deskripsi toko maksimal 1000 karakter.',
        ];

        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'store_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ], $messages);

        // Save store name to both user->name (legacy) and users.shop_name when available
        $updates = [
            'name' => $data['store_name'],
        ];

        if (Schema::hasColumn('users', 'shop_name')) {
            $updates['shop_name'] = $data['store_name'];
        }

        // Handle shop photo upload
        if ($request->hasFile('store_photo')) {
            $file = $request->file('store_photo');
            $filename = 'shop-' . $user->id . '-' . Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('public_app_public')->putFileAs('shop-photos', $file, $filename);
            $storedPath = 'app/public/shop-photos/' . $filename;
            if (Schema::hasColumn('users', 'shop_photo')) {
                $updates['shop_photo'] = $storedPath;
            } else {
                // fallback: store in profile avatar if shop_photo column missing
                $profileAvatar = $storedPath;
                $user->profile()->updateOrCreate(['user_id' => $user->id], ['avatar_path' => $profileAvatar]);
            }
        }

        if (! empty($data['bio'])) {
            if (Schema::hasColumn('users', 'shop_description')) {
                $updates['shop_description'] = $data['bio'];
            }

            $user->profile()->updateOrCreate(['user_id' => $user->id], ['bio' => $data['bio']]);
        }

        $user->forceFill($updates)->save();

        return back()->with('success', 'Profil toko berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $user->forceFill([
            'is_seller' => false,
            'user_type' => 'buyer',
        ])->save();

        $user->products()->update(['status' => 'archived']);

        return redirect()->route('dashboard')->with('success', 'Toko dan status seller Anda telah dihapus.');
    }
}
