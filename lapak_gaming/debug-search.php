<?php

// Quick debug script to check products and categories
require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

// Initialize the application
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DATABASE DEBUG SCRIPT ===\n\n";

// Check categories
echo "1. CATEGORIES IN DATABASE:\n";
$categories = Category::all();
echo "Total categories: " . $categories->count() . "\n";
foreach ($categories as $cat) {
    echo "  - ID: {$cat->id}, Name: {$cat->name}, Slug: {$cat->slug}, Parent: {$cat->parent_id}\n";
}

echo "\n2. PRODUCTS WITH status='published' AND stock > 0:\n";
$activeProducts = Product::where('status', 'published')->where('stock', '>', 0)->get();
echo "Total active products: " . $activeProducts->count() . "\n";
if ($activeProducts->count() > 0) {
    foreach ($activeProducts->take(5) as $prod) {
        $cat = $prod->category;
        echo "  - ID: {$prod->id}, Name: {$prod->name}, Stock: {$prod->stock}, Category: {$cat?->name} (ID: {$prod->category_id})\n";
    }
    if ($activeProducts->count() > 5) {
        echo "  ... and " . ($activeProducts->count() - 5) . " more\n";
    }
} else {
    echo "  NO PRODUCTS FOUND!\n";
}

echo "\n3. SEARCH TEST - Searching for 'Mobile Legends':\n";
$searchResults = Product::active()->inStock()->where('name', 'like', '%Mobile Legends%')->get();
echo "Results: " . $searchResults->count() . " products\n";
foreach ($searchResults->take(3) as $prod) {
    echo "  - {$prod->name}\n";
}

echo "\n4. CATEGORY SEARCH - Looking for 'top-up-game':\n";
$topupCategory = Category::where('slug', 'top-up-game')->first();
if ($topupCategory) {
    echo "  Found: {$topupCategory->name} (ID: {$topupCategory->id})\n";
    $productsInCategory = Product::active()->inStock()->where('category_id', $topupCategory->id)->get();
    echo "  Products in this category: " . $productsInCategory->count() . "\n";
    foreach ($productsInCategory->take(3) as $prod) {
        echo "    - {$prod->name} (stock: {$prod->stock})\n";
    }
} else {
    echo "  NOT FOUND!\n";
}

echo "\n5. RAW SQL - Check status and stock values:\n";
$statusCounts = DB::table('products')->groupBy('status')->selectRaw('status, COUNT(*) as count')->get();
echo "Products by status:\n";
foreach ($statusCounts as $row) {
    echo "  - {$row->status}: {$row->count} products\n";
}

$stockCounts = DB::table('products')->selectRaw('COUNT(*) as total, COUNT(CASE WHEN stock > 0 THEN 1 END) as in_stock, COUNT(CASE WHEN stock = 0 THEN 1 END) as out_stock')->first();
echo "\nStock status:\n";
echo "  - Total products: {$stockCounts->total}\n";
echo "  - In stock (stock > 0): {$stockCounts->in_stock}\n";
echo "  - Out of stock (stock = 0): {$stockCounts->out_stock}\n";

echo "\n=== END DEBUG ===\n";
