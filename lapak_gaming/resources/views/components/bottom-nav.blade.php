<div class="fixed bottom-0 left-0 right-0 z-[100] lg:hidden">
    <div class="mx-4 mb-4 rounded-2xl border border-slate-800 bg-slate-900/95 backdrop-blur-xl shadow-2xl">
        
        <div class="grid grid-cols-5">

            <a href="{{ route('marketplace.home') }}"
               class="flex flex-col items-center justify-center py-3 text-slate-400 hover:text-blue-400 transition">
                <span class="text-lg">🏠</span>
                <span class="text-[10px] mt-1">Home</span>
            </a>

            <a href="{{ route('marketplace.browse') }}"
               class="flex flex-col items-center justify-center py-3 text-slate-400 hover:text-blue-400 transition">
                <span class="text-lg">🎮</span>
                <span class="text-[10px] mt-1">Browse</span>
            </a>

            <a href="{{ route('cart.index') }}"
               class="flex flex-col items-center justify-center py-3 text-slate-400 hover:text-blue-400 transition relative">
                <span class="text-lg">🛒</span>
                <span class="text-[10px] mt-1">Cart</span>

                @if(isset($cartCount) && $cartCount > 0)
                    <span class="absolute top-2 right-5 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] text-white">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            @auth
            <a href="{{ route('wishlist.index') }}"
               class="flex flex-col items-center justify-center py-3 text-slate-400 hover:text-blue-400 transition">
                <span class="text-lg">❤️</span>
                <span class="text-[10px] mt-1">Wishlist</span>
            </a>
            @else
            <a href="{{ route('login') }}"
               class="flex flex-col items-center justify-center py-3 text-slate-400 hover:text-blue-400 transition">
                <span class="text-lg">❤️</span>
                <span class="text-[10px] mt-1">Wishlist</span>
            </a>
            @endauth

            @auth
            <a href="{{ route('dashboard') }}"
               class="flex flex-col items-center justify-center py-3 text-slate-400 hover:text-blue-400 transition">
                <span class="text-lg">👤</span>
                <span class="text-[10px] mt-1">Account</span>
            </a>
            @else
            <a href="{{ route('login') }}"
               class="flex flex-col items-center justify-center py-3 text-slate-400 hover:text-blue-400 transition">
                <span class="text-lg">👤</span>
                <span class="text-[10px] mt-1">Login</span>
            </a>
            @endauth

        </div>
    </div>
</div>