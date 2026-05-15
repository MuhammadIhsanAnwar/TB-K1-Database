<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SellerRegistrationController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        // Already an approved seller
        if ($user && $user->isSellerAccount()) {
            return redirect()->route('seller.dashboard')
                ->with('success', 'Akun Anda sudah terdaftar sebagai seller.');
        }

        // Already has a pending application
        if ($user && $user->hasPendingSellerApplication()) {
            return back()->with('info', 'Pengajuan seller Anda sedang menunggu verifikasi admin.');
        }

        return view('seller.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Guard: already a seller
        if ($user->isSellerAccount()) {
            return redirect()->route('seller.dashboard')
                ->with('success', 'Akun Anda sudah terdaftar sebagai seller.');
        }

        // Guard: pending application already submitted
        if ($user->hasPendingSellerApplication()) {
            return redirect()->route('buyer.dashboard')
                ->with('info', 'Pengajuan seller Anda sedang menunggu verifikasi admin.');
        }

        $messages = [
            'shop_name.required'         => 'Nama toko wajib diisi.',
            'shop_name.max'              => 'Nama toko maksimal 255 karakter.',
            'shop_photo.required'        => 'Foto profil toko wajib diunggah.',
            'shop_photo.image'           => 'File harus berupa gambar (jpg, jpeg, png, webp).',
            'shop_photo.mimes'           => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'shop_photo.max'             => 'Ukuran gambar maksimal 5 MB.',
            'shop_description.required'  => 'Deskripsi toko wajib diisi.',
            'shop_description.max'       => 'Deskripsi toko maksimal 1000 karakter.',
        ];

        $data = $request->validate([
            'shop_name'        => ['required', 'string', 'max:255'],
            'shop_photo'       => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'shop_description' => ['required', 'string', 'max:1000'],
        ], $messages);

        // Store shop photo
        $file     = $request->file('shop_photo');
        $filename = 'shop-' . $user->id . '-' . Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();

        // Use the same disk convention as the rest of the project
        $shopPhotoPath = Storage::disk('public_app_public')
            ->putFileAs('shop-photos', $file, $filename);

        $updates = [
            'shop_name'        => $data['shop_name'],
            'shop_photo'       => 'app/public/shop-photos/' . $filename,
            'shop_description' => $data['shop_description'],
            'seller_status'    => 'pending',
        ];

        // Legacy columns — set if they exist in the schema
        if (Schema::hasColumn('users', 'is_seller')) {
            $updates['is_seller'] = false; // will be set true only after admin approval
        }

        $user->forceFill($updates)->save();

        return redirect()->route('buyer.dashboard')
            ->with('success', 'Pengajuan seller Anda berhasil dikirim! Admin akan meninjau dan memverifikasi toko Anda dalam 1–3 hari kerja.');
    }
}