<nav class="bg-gray-900 border-b border-gray-800 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-violet-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">LG</div>
                <span class="font-bold text-lg text-white">Lapak <span class="text-violet-400">Geming</span></span>
            </a>

            {{-- Search Bar --}}
            <div class="hidden md:flex flex-1 max-w-xl mx-8">
                <form action="{{ route('products.search') }}" method="GET" class="w-full flex">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari game, item, top up..."
                        class="w-full bg-gray-800 border border-gray-700 text-gray-100 rounded-l-lg px-4 py-2 text-sm focus:outline-none focus:border-violet-500 placeholder-gray-500"
                    >
                    <button type="submit" class="bg-violet-600 hover:bg-violet-700 px-4 rounded-r-lg transition">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Nav Links --}}
            <div class="flex items-center gap-4">
                @auth
                    {{-- Cart Icon --}}
                    <a href="{{ route('cart.index') }}" class="relative text-gray-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.4 7M7 13l-4-8m10 8l1.4 7M17 13l1.4 7M9 20a1 1 0 100 2 1 1 0 000-2zm8 0a1 1 0 100 2 1 1 0 000-2z"/>
                        </svg>
                        @php $cartCount = auth()->user()->cart()->count(); @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-violet-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">{{ $cartCount }}</span>
                        @endif
                    </a>

                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 text-gray-300 hover:text-white transition">
                            <img src="{{ auth()->user()->avatar_url }}" class="w-8 h-8 rounded-full object-cover border-2 border-gray-700" alt="Avatar">
                            <span class="hidden md:block text-sm">{{ auth()->user()->name }}</span>
                        </button>
                        <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-48 bg-gray-800 border border-gray-700 rounded-xl shadow-xl py-2">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Dashboard</a>
                            <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Pesanan Saya</a>
                            @if(auth()->user()->isSeller() || auth()->user()->isAdmin())
                                <a href="{{ route('seller.dashboard') }}" class="block px-4 py-2 text-sm text-violet-400 hover:bg-gray-700">Seller Dashboard</a>
                            @endif
                            <hr class="border-gray-700 my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700">Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-white transition">Masuk</a>
                    <a href="{{ route('register') }}" class="text-sm bg-violet-600 hover:bg-violet-700 text-white px-4 py-2 rounded-lg transition">Daftar</a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Category Bar --}}
    <div class="border-t border-gray-800 overflow-x-auto">
        <div class="max-w-7xl mx-auto px-4 flex gap-1 py-2">
            @foreach(['topup' => 'Top Up', 'item' => 'Item', 'akun' => 'Akun', 'voucher' => 'Voucher', 'gamekey' => 'Game Key'] as $type => $label)
                <a href="{{ route('products.by-type', $type) }}"
                   class="whitespace-nowrap text-xs px-3 py-1.5 rounded-full {{ request()->route('type') === $type ? 'bg-violet-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white' }} transition">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>
</nav>