<a href="{{ route('products.show', $product->slug) }}"
   class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden hover:border-cyan-600 hover:shadow-lg hover:shadow-cyan-900/20 transition-all group">
    <div class="aspect-square overflow-hidden bg-gray-800">
        <img src="{{ $product->image_url }}"
             alt="{{ $product->name }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
             loading="lazy">
    </div>
    <div class="p-3">
        <p class="text-xs text-cyan-400 mb-1">{{ $product->category->name ?? '' }}</p>
        <h3 class="text-sm font-medium text-gray-100 line-clamp-2 leading-tight mb-2">{{ $product->name }}</h3>
        <p class="text-base font-bold text-white">{{ $product->formatted_price }}</p>
        <div class="flex items-center justify-between mt-2">
            <div class="flex items-center gap-1">
                <span class="text-yellow-400 text-xs">★</span>
                <span class="text-xs text-gray-400">{{ number_format($product->rating, 1) }}</span>
                <span class="text-xs text-gray-600">({{ $product->review_count }})</span>
            </div>
            <span class="text-xs text-gray-500">{{ number_format($product->sold_count) }} terjual</span>
        </div>
    </div>
</a>