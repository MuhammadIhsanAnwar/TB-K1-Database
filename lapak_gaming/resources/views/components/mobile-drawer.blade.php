<div x-show="mobileMenuOpen" x-cloak @keydown.escape.window="mobileMenuOpen = false" class="drawer-panel lg:hidden" x-transition:enter="transition duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition duration-250" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
    <div class="flex items-center justify-between px-4 py-4 border-b border-default">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-primary to-cyan-500 flex items-center justify-center shadow-glow text-white">
                <span class="font-display font-bold">LG</span>
            </div>
            <div>
                <a href="{{ route('marketplace.home') }}" class="text-base font-semibold text-white">Lapak Gaming</a>
                <p class="text-sm text-secondary">Marketplace terbaik untuk gamer</p>
            </div>
        </div>
        <button @click="mobileMenuOpen = false" class="btn-icon bg-elevated border border-default text-muted hover:text-white hover:bg-surface transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <div class="px-4 py-4 border-b border-default">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-secondary pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" placeholder="Search marketplace" class="input-gaming pl-10 pr-3" />
        </div>
    </div>

    <nav class="px-4 py-4 space-y-2">
        <a href="{{ route('marketplace.home') }}" class="nav-pill w-full {{ request()->routeIs('marketplace.home') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-secondary' }}">
            Home
        </a>
        <a href="{{ route('marketplace.browse') }}" class="nav-pill w-full {{ request()->routeIs('marketplace.browse') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-secondary' }}">
            Browse All
        </a>
        <a href="{{ route('marketplace.trending') }}" class="nav-pill w-full {{ request()->routeIs('marketplace.trending') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-secondary' }}">
            Trending
        </a>
        <a href="{{ route('marketplace.deals') }}" class="nav-pill w-full {{ request()->routeIs('marketplace.deals') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-secondary' }}">
            Deals
        </a>

        @auth
            <a href="{{ route('wishlist.index') }}" class="nav-pill w-full {{ request()->routeIs('wishlist.index') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-secondary' }}">
                Wishlist
            </a>
            <a href="{{ route('profile.show') }}" class="nav-pill w-full text-secondary">
                Profile
            </a>
        @else
            <a href="{{ route('login') }}" class="nav-pill w-full text-secondary">
                Login
            </a>
            <a href="{{ route('register') }}" class="btn-primary w-full justify-center">
                Sign Up
            </a>
        @endauth
    </nav>

    @auth
    <div class="px-4 pb-4 border-t border-default">
        <div class="glass-card p-4">
            <p class="text-sm text-secondary">Upgrade ke Lapak Gaming Pro</p>
            <p class="mt-1 text-white font-semibold">Dapatkan fitur eksklusif & promosi</p>
            <a href="{{ route('subscription.upgrade') }}" class="mt-4 inline-flex w-full items-center justify-center btn-cyan">
                Upgrade Now
            </a>
        </div>
    </div>
    @endauth
</div>