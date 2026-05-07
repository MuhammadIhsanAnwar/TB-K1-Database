<div 
    class="fixed inset-y-0 left-0 z-[120] w-72 bg-slate-900 border-r border-slate-800 transform transition-transform duration-300 lg:hidden"
    :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <div class="flex items-center justify-between p-4 border-b border-slate-800">
        <h2 class="text-lg font-bold text-white">
            Menu
        </h2>

        <button 
            @click="mobileMenuOpen = false"
            class="text-slate-400 hover:text-white"
        >
            ✕
        </button>
    </div>

    <nav class="p-4 space-y-2">

        <a href="{{ route('marketplace.home') }}"
           class="block px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition">
            Home
        </a>

        <a href="{{ route('marketplace.browse') }}"
           class="block px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition">
            Browse
        </a>

        <a href="{{ route('cart.index') }}"
           class="block px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition">
            Cart
        </a>

        @auth
            <a href="{{ route('dashboard') }}"
               class="block px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition">
                Dashboard
            </a>
        @endauth

    </nav>
</div>