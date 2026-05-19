<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SellerProductController extends Controller
{

    public function dashboard()
    {
        $seller = Auth::user();

        // Ambil data produk untuk dihitung di Blade
        $products = Product::where('seller_id', $seller->id)->get();

        // Ambil data order terkait seller ini (via OrderItem)
        $orders = OrderItem::where('seller_id', $seller->id)
            ->with(['order', 'product'])
            ->latest()
            ->get();

        return view('dashboard.seller', compact('seller', 'products', 'orders'));
    }

    /**
     * Attempt to compress an image file stored on the public disk.
     * This is best-effort and will silently skip if GD/WEBP is not available.
     */
    private function compressImageOnDisk(string $relativePath): void
    {
        try {
            $full = storage_path('app/public/' . ltrim($relativePath, '/'));
            if (!file_exists($full)) return;

            $ext = strtolower(pathinfo($full, PATHINFO_EXTENSION));
            // Only handle common raster formats
            if (!in_array($ext, ['jpg','jpeg','png','webp'])) return;

            $data = file_get_contents($full);
            if (!$data) return;

            $img = @imagecreatefromstring($data);
            if (!$img) return;

            // Get original dimensions and resize if it exceeds max dimension
            $width = imagesx($img);
            $height = imagesy($img);
            $maxDim = 1200;

            if ($width > $maxDim || $height > $maxDim) {
                if ($width > $height) {
                    $newWidth = $maxDim;
                    $newHeight = (int)($height * ($maxDim / $width));
                } else {
                    $newHeight = $maxDim;
                    $newWidth = (int)($width * ($maxDim / $height));
                }

                $resizedImg = imagecreatetruecolor($newWidth, $newHeight);

                // Preserve transparency for PNG and WebP
                if ($ext === 'png' || $ext === 'webp') {
                    imagealphablending($resizedImg, false);
                    imagesavealpha($resizedImg, true);
                    $transparent = imagecolorallocatealpha($resizedImg, 0, 0, 0, 127);
                    imagefill($resizedImg, 0, 0, $transparent);
                }

                imagecopyresampled($resizedImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($img);
                $img = $resizedImg;
            }

            // Save back in original format or webp
            if ($ext === 'webp' && function_exists('imagewebp')) {
                imagewebp($img, $full, 80);
            } elseif (in_array($ext, ['jpg', 'jpeg']) && function_exists('imagejpeg')) {
                imagejpeg($img, $full, 80);
            } elseif ($ext === 'png') {
                if (function_exists('imagewebp') && filesize($full) > 500000) {
                    // Convert large PNG to webp to save bandwidth while keeping the extension
                    imagewebp($img, $full, 80);
                } elseif (function_exists('imagepng')) {
                    imagepng($img, $full, 6);
                }
            } else {
                if (function_exists('imagewebp')) {
                    imagewebp($img, $full, 80);
                }
            }

            imagedestroy($img);
        } catch (\Throwable $e) {
            // best-effort: ignore failures
        }
    }

    public function index()
    {
        $status = request('status', 'active');

        $products = Product::where('seller_id', Auth::id())
            ->with(['category'])
            ->when($status === 'active', fn($query) => $query->active(), fn($query) => $query->archived())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('seller.products.index', compact('products', 'status'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk maksimal 255 karakter.',
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'description.string' => 'Deskripsi produk harus berupa teks.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga produk harus berupa angka.',
            'price.min' => 'Harga produk minimal 100.',
            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok produk harus berupa bilangan bulat.',
            'stock.min' => 'Stok produk tidak boleh kurang dari 0.',
            'type.required' => 'Jenis produk wajib dipilih.',
            'type.in' => 'Jenis produk tidak valid.',
            'images.array' => 'Foto produk harus dikirim sebagai daftar file.',
            'images.*.image' => 'Setiap file foto harus berupa gambar.',
            'images.*.mimes' => 'Format foto harus jpg, jpeg, png, atau webp.',
            'images.*.max' => 'Ukuran tiap foto maksimal 5 MB.',
        ];

        $request->merge([
            'name' => trim(strtolower($request->name))
        ]);

        $validated = $request->validate([
            'name' => ['required','string','max:255', Rule::unique('products')->where(fn($q) => $q->where('seller_id', Auth::id()))],
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:100',
            'stock' => 'required|integer|min:0',
            'type' => 'required|in:topup,item,akun,voucher,gamekey',
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], $messages);

        $imagePaths = [];

        // 1. Proses multiple image secara aman ke public disk (storage/app/public)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $filename = Str::uuid()->toString() . '.' . $imageFile->getClientOriginalExtension();
                $stored = $imageFile->storeAs('foto_produk', $filename, 'public');
                $imagePaths[] = $stored;
                // attempt to compress image to save bandwidth (best-effort)
                $this->compressImageOnDisk($stored);
            }
        }

        // 2. Proses single image (fallback kalau inputnya cuma 'image')
        if (empty($imagePaths) && $request->hasFile('image')) {
            $legacyImage = $request->file('image');
            $filename = Str::uuid()->toString() . '.' . $legacyImage->getClientOriginalExtension();
            $stored = $legacyImage->storeAs('foto_produk', $filename, 'public');
            $imagePaths[] = $stored;
            $this->compressImageOnDisk($stored);
        }

        // ─── LOGIKA PEMBAGIAN KOLOM (SUDAH BENAR) ──────────────────────────
        if (!empty($imagePaths)) {
            $validated['image'] = $imagePaths[0]; // Gambar utama
            $validated['file_path'] = implode('|', $imagePaths); // Semua gambar
        } else {
            $validated['image'] = null;
            $validated['file_path'] = null;
        }

        $validated['seller_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $validated['status'] = 'published';

        Product::create($validated);

        return redirect()->route('seller.produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $produk)
    {
        abort_if((int) $produk->seller_id !== (int) Auth::id(), 403);
        $categories = Category::all();
        return view('seller.products.edit', compact('produk', 'categories'));
    }

    public function update(Request $request, Product $produk)
    {
        abort_if((int) $produk->seller_id !== (int) Auth::id(), 403);

        $messages = [
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'price.required' => 'Harga produk wajib diisi.',
            'stock.required' => 'Stok produk wajib diisi.',
            'status.required' => 'Status produk wajib dipilih.',
            'images.array' => 'Foto produk harus dikirim sebagai daftar file.',
            'images.*.max' => 'Ukuran tiap foto maksimal 5 MB.',
        ];

        $request->merge([
            'name' => trim(strtolower($request->name))
        ]);

        $validated = $request->validate([
            'name' => ['required','string','max:255', Rule::unique('products')->ignore($produk->id)->where(fn($q) => $q->where('seller_id', Auth::id()))],
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:100',
            'stock' => 'required|integer|min:0',
            'type' => 'required|in:topup,item,akun,voucher,gamekey',
            'status' => 'required|in:draft,published,archived',
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'removed_images' => ['nullable', 'json'],
        ], $messages);

        $imagePaths = $produk->image_paths;

        // Handle removed images
        if ($request->has('removed_images') && $request->input('removed_images') !== '[]') {
            $removedIndices = json_decode($request->input('removed_images'), true) ?? [];

            foreach ($removedIndices as $index) {
                if (isset($imagePaths[$index])) {
                    if (Storage::disk('public')->exists($imagePaths[$index])) {
                        Storage::disk('public')->delete($imagePaths[$index]);
                    } elseif (file_exists(public_path($imagePaths[$index]))) {
                        @unlink(public_path($imagePaths[$index]));
                    }
                    unset($imagePaths[$index]);
                }
            }

            // Reindex array after removal
            $imagePaths = array_values($imagePaths);
        }

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $filename = Str::uuid()->toString() . '.' . $imageFile->getClientOriginalExtension();
                $stored = $imageFile->storeAs('foto_produk', $filename, 'public');
                $imagePaths[] = $stored;
                $this->compressImageOnDisk($stored);
            }
        } elseif ($request->hasFile('image')) {
            $legacyImage = $request->file('image');
            $filename = Str::uuid()->toString() . '.' . $legacyImage->getClientOriginalExtension();
            $stored = $legacyImage->storeAs('foto_produk', $filename, 'public');
            $imagePaths[] = $stored;
            $this->compressImageOnDisk($stored);
        }

        if ($imagePaths) {
            $validated['file_path'] = implode('|', $imagePaths);
        } else {
            $validated['file_path'] = null;
        }

        $produk->update($validated);

        return redirect()->route('seller.produk.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $produk)
    {
        abort_if((int) $produk->seller_id !== (int) Auth::id(), 403);
        $produk->update(['status' => 'archived']);
        return back()->with('success', 'Produk berhasil diarsipkan.');
    }

    public function activate(Product $produk)
    {
        abort_if((int) $produk->seller_id !== (int) Auth::id(), 403);
        $produk->update(['status' => 'published']);
        return back()->with('success', 'Produk berhasil diaktifkan kembali.');
    }

    public function forceDestroy(Product $produk)
    {
        abort_if((int) $produk->seller_id !== (int) Auth::id(), 403);

        if ($produk->orderItems()->exists()) {
            return back()->withErrors([
                'delete' => 'Produk yang sudah memiliki riwayat pesanan tidak bisa dihapus permanen. Silakan arsipkan saja.',
            ]);
        }

        foreach ($produk->image_paths as $existingImage) {
            Storage::disk('public')->delete($existingImage);
        }

        $produk->delete();

        return back()->with('success', 'Produk berhasil dihapus permanen.');
    }
}