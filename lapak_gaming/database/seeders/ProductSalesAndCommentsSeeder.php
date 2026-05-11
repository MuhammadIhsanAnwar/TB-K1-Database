<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductComment;
use App\Models\CommentLike;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderFinancial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSalesAndCommentsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating sales data and comments for products...');

        $products = Product::active()->inStock()->limit(50)->get();
        $buyers = User::withoutTrashed()->where('role', 'buyer')->get();

        foreach ($products as $product) {
            if ($buyers->isEmpty()) break;

            // Create random sales (orders) for this product
            $soldCount = random_int(10, 120);

            for ($i = 0; $i < $soldCount; $i++) {
                $buyer = $buyers->random();
                $quantity = random_int(1, 5);

                // Create order
                $order = Order::create([
                    'buyer_id' => $buyer->id,
                    'seller_id' => $product->seller_id,
                    'order_number' => 'ORD-' . date('YmdHis') . '-' . random_int(1000, 9999),
                    'subtotal' => $product->price * $quantity,
                    'shipping_cost' => 0,
                    'tax' => 0,
                    'total' => $product->price * $quantity,
                    'payment_method' => collect(['wallet', 'bank_transfer', 'credit_card'])->random(),
                    'status' => 'completed',
                    'paid_at' => now()->subDays(random_int(1, 90)),
                    'completed_at' => now()->subDays(random_int(0, 89)),
                ]);

                // Create order item
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'seller_id' => $product->seller_id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $product->price * $quantity,
                    'status' => 'completed',
                    'delivered_at' => now()->subDays(random_int(0, 80)),
                ]);

                // Create order financial
                OrderFinancial::create([
                    'order_id' => $order->id,
                    'seller_id' => $product->seller_id,
                    'gross_amount' => $product->price * $quantity,
                    'fee_amount' => ($product->price * $quantity) * 0.05, // 5% fee
                    'net_amount' => ($product->price * $quantity) * 0.95,
                    'status' => 'settled',
                ]);

                // ~70% chance buyer leaves a comment/rating
                if (random_int(1, 100) <= 70) {
                    $rating = collect([5, 5, 5, 4, 4, 3])->random();
                    $commentContent = $this->getRandomComment($rating);

                    $comment = ProductComment::create([
                        'product_id' => $product->id,
                        'user_id' => $buyer->id,
                        'content' => $commentContent,
                        'rating' => $rating,
                        'is_verified_buyer' => true,
                        'status' => 'approved',
                    ]);

                    // ~40% chance seller replies
                    if (random_int(1, 100) <= 40) {
                        $sellerReply = ProductComment::create([
                            'product_id' => $product->id,
                            'user_id' => $product->seller_id,
                            'parent_comment_id' => $comment->id,
                            'content' => $this->getRandomSellerReply($rating),
                            'status' => 'approved',
                        ]);

                        $comment->increment('replies_count');
                    }

                    // Some other users like the comment
                    $likeCount = random_int(0, 15);
                    for ($j = 0; $j < $likeCount; $j++) {
                        $liker = $buyers->random();
                        if ($liker->id !== $comment->user_id) {
                            try {
                                CommentLike::create([
                                    'product_comment_id' => $comment->id,
                                    'user_id' => $liker->id,
                                ]);
                                $comment->increment('likes_count');
                            } catch (\Exception $e) {
                                // Ignore duplicate likes
                            }
                        }
                    }
                }
            }

            // Update product statistics
            $product->statistics()->update([
                'sold_count' => $soldCount,
                'rating_average' => DB::table('product_comments')
                    ->where('product_id', $product->id)
                    ->where('status', 'approved')
                    ->whereNotNull('rating')
                    ->avg('rating') ?? 0,
                'review_count' => ProductComment::where('product_id', $product->id)
                    ->where('status', 'approved')
                    ->whereNotNull('rating')
                    ->count(),
            ]);

            $this->command->line("✓ Created sales and comments for: {$product->name}");
        }

        $this->command->info('Product sales and comments seeding completed!');
    }

    private function getRandomComment(int $rating): string
    {
        $positiveComments = [
            'Produk bagus, sesuai deskripsi, puas dengan pembelian ini!',
            'Kualitas terbaik! Rekomendasi untuk yang lain.',
            'Sangat memuaskan, seller responsif dan cepat mengirim.',
            'Produk berkualitas tinggi, worth it dengan harga.',
            'Pelayanan seller luar biasa, produk original 100%.',
            'Packaging rapi, produk dalam kondisi sempurna.',
            'Sudah pakai beberapa minggu, masih like new.',
            'Sesuai dengan foto, tidak ada yang mengecewakan.',
            'Top seller! Rekomendasi banget deh.',
            'Mantap, sesuai ekspektasi dan lebih dari itu.',
        ];

        $neutralComments = [
            'Produk cukup bagus, tapi pengiriman lumayan lama.',
            'Baik, tapi bisa lebih baik dalam packaging.',
            'Sesuai ekspektasi, biasa saja.',
            'Produk ok, hanya sedikit berbeda dari foto.',
            'Lumayan, tapi harganya sedikit mahal.',
            'Cukup memuaskan untuk pembelian pertama.',
        ];

        $negativeComments = [
            'Produk cacat, tidak sesuai deskripsi.',
            'Kecewa dengan kualitasnya.',
            'Tidak boleh dibeli, produk jelek.',
            'Seller tidak responsif, kecewa.',
        ];

        if ($rating >= 5) {
            return $positiveComments[array_rand($positiveComments)];
        } elseif ($rating >= 3) {
            return $neutralComments[array_rand($neutralComments)];
        } else {
            return $negativeComments[array_rand($negativeComments)];
        }
    }

    private function getRandomSellerReply(int $rating): string
    {
        $replies = [
            'Terima kasih telah berbelanja! Kami senang produk Anda puas. Silakan hubungi kami untuk pembelian selanjutnya!',
            'Apresiasi reviewer! Kami akan terus meningkatkan kualitas layanan kami.',
            'Makasih sudah percaya, semoga next order bisa lebih baik lagi!',
            'Terima kasih untuk ulasan positifnya! Kepuasan pelanggan adalah prioritas kami.',
            'Senang mendengar produk Anda berkesan! Jangan ragu untuk order lagi!',
            'Maaf mendengar pengalaman Anda tidak memuaskan. Kami akan membuat solusi terbaik untuk Anda.',
            'Terima kasih feedbacknya, kami akan perbaiki di masa depan.',
            'Kami mohon maaf atas ketidakpuasan Anda. Hubungi kami langsung untuk penyelesaian.',
        ];

        return $replies[array_rand($replies)];
    }
}
