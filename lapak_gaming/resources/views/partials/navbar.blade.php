<header class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/80 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/80">
    <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3 font-extrabold tracking-tight text-slate-950 dark:text-white">
            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-950 text-sm text-white shadow-glow dark:bg-white dark:text-slate-950">LG</span>
            <span>Lapak Digital</span>
        </a>

        <nav class="hidden items-center gap-3 md:flex">
            <a href="{{ route('home') }}" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Marketplace</a>
            @auth
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Panel Admin</a>
                    <a href="{{ route('admin.users.index') }}" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Kelola Akun</a>
                    <a href="{{ route('admin.orders.index') }}" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Transaksi</a>
                @else
                    <a href="{{ route('dashboard') }}" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Dashboard</a>
                    <a href="{{ route('wallet.index') }}" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Wallet</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">Login</a>
                <a href="{{ route('register') }}" class="rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950">Register</a>
            @endauth
            <button type="button" onclick="toggleTheme()" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                Theme
            </button>
        </nav>

        <button type="button" onclick="toggleTheme()" class="rounded-full border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 md:hidden dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            Theme
        </button>
    </div>
</header>