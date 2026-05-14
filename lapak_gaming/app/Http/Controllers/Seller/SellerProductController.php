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

class SellerProductController extends Controller {
    
    public function dashboard() {
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

    public function index() {
        $products = Product::where('seller_id', Auth::id())
            ->with(['category'])
            ->latest()
            ->paginate(15);
        return view('seller.products.index', compact('products'));
    }

    public function create() {
        $categories = Category::all();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request) {
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

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:100',
            'stock'       => 'required|integer|min:0',
            'type'        => 'required|in:topup,item,akun,voucher,gamekey',
            'images'      => ['nullable', 'array', 'max:10'],
            'images.*'    => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], $messages);

        $imagePaths = [];
        foreach ($request->file('images', []) as $imageFile) {
            $filename = Str::uuid()->toString() . '.' . $imageFile->getClientOriginalExtension();
            $imagePaths[] = $imageFile->storeAs('foto_produk', $filename, 'public');
        }

        if (empty($imagePaths) && $request->hasFile('image')) {
            $legacyImage = $request->file('image');
            $filename = Str::uuid()->toString() . '.' . $legacyImage->getClientOriginalExtension();
            $imagePaths[] = $legacyImage->storeAs('foto_produk', $filename, 'public');
        }

        if ($imagePaths) {
            $validated['file_path'] = implode('|', $imagePaths);
        }

        $validated['seller_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $validated['status'] = 'published';

        Product::create($validated);

        return redirect()->route('seller.produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $produk) {
        abort_if((int) $produk->seller_id !== (int) Auth::id(), 403);
        $categories = Category::all();
        return view('seller.products.edit', compact('produk', 'categories'));
    }

    public function update(Request $request, Product $produk) {
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

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:100',
            'stock'       => 'required|integer|min:0',
            'type'        => 'required|in:topup,item,akun,voucher,gamekey',
            'status'      => 'required|in:draft,published,archived',
            'images'      => ['nullable', 'array', 'max:10'],
            'images.*'    => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], $messages);

        $imagePaths = $produk->image_paths;
        if ($request->hasFile('images')) {
            foreach ($imagePaths as $existingImage) {
                Storage::disk('public')->delete($existingImage);
            }

            $imagePaths = [];
            foreach ($request->file('images') as $imageFile) {
                $filename = Str::uuid()->toString() . '.' . $imageFile->getClientOriginalExtension();
                $imagePaths[] = $imageFile->storeAs('foto_produk', $filename, 'public');
            }
        } elseif ($request->hasFile('image')) {
            foreach ($imagePaths as $existingImage) {
                Storage::disk('public')->delete($existingImage);
            }

            $legacyImage = $request->file('image');
            $filename = Str::uuid()->toString() . '.' . $legacyImage->getClientOriginalExtension();
            $imagePaths = [$legacyImage->storeAs('foto_produk', $filename, 'public')];
        }

        if ($imagePaths) {
            $validated['file_path'] = implode('|', $imagePaths);
        }

        $produk->update($validated);

        return redirect()->route('seller.produk.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $produk) {
        abort_if((int) $produk->seller_id !== (int) Auth::id(), 403);
        // Mengubah status jadi archived (Soft Delete manual)
        $produk->update(['status' => 'archived']);
        return back()->with('success', 'Produk berhasil diarsipkan.');
    }
}