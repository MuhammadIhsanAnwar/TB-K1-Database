<div class="bottom-nav lg:hidden">
    <div class="mx-4 mb-4 rounded-3xl border border-default bg-elevated/95 backdrop-blur-xl shadow-elevated overflow-hidden">
        <div class="grid grid-cols-5">

            <a href="{{ route('marketplace.home') }}"
               class="flex flex-col items-center justify-center py-3 text-secondary hover:text-primary transition-colors">
                <span class="text-lg">🏠</span>
                <span class="text-[10px] mt-1">Home</span>
            </a>

            <a href="{{ route('marketplace.browse') }}"
               class="flex flex-col items-center justify-center py-3 text-secondary hover:text-primary transition-colors">
                <span class="text-lg">🎮</span>
                <span class="text-[10px] mt-1">Browse</span>
            </a>

            <a href="{{ route('cart.index') }}"
               class="flex flex-col items-center justify-center py-3 text-secondary hover:text-primary transition-colors relative">
                <span class="text-lg">🛒</span>
                <span class="text-[10px] mt-1">Cart</span>

                @if(isset($cartCount) && $cartCount > 0)
                    <span class="absolute top-2 right-5 flex h-4 min-w-[18px] items-center justify-center rounded-full bg-cyan-500 text-[10px] font-semibold text-black px-1">
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