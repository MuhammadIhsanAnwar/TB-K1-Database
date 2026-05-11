<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update(['name' => $data['store_name']]);
        $user->profile()->updateOrCreate([
            'user_id' => $user->id,
        ], [
            'bio' => $data['bio'],
        ]);

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
