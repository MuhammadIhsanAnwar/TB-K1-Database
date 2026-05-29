@extends('layouts.app')

@section('title', 'Verifikasi Seller — Admin')

@push('styles')
<style>
/* ==========================================================
VERIFICATION CENTER 2026
Premium SaaS Dashboard
Author: ChatGPT
========================================================== */

:root{
--aurora-1:#7c3aed;
--aurora-2:#2563eb;
--aurora-3:#06b6d4;
--aurora-4:#22c55e;
--aurora-5:#f59e0b;
--aurora-6:#ef4444;

```
--glass-bg:rgba(255,255,255,.05);
--glass-border:rgba(255,255,255,.08);

--shadow-soft:
    0 10px 30px rgba(0,0,0,.15);

--shadow-hover:
    0 25px 50px rgba(0,0,0,.25);

--radius-xl:24px;
```

}

/* ==========================================================
BACKGROUND
========================================================== */

body{
position:relative;
overflow-x:hidden;
}

body::before{
content:'';
position:fixed;
inset:-40%;
z-index:-2;

```
background:
    radial-gradient(circle at 20% 20%, rgba(124,58,237,.25), transparent 30%),
    radial-gradient(circle at 80% 20%, rgba(6,182,212,.20), transparent 30%),
    radial-gradient(circle at 50% 80%, rgba(34,197,94,.15), transparent 30%);

animation:auroraMove 30s linear infinite;
```

}

body::after{
content:'';
position:fixed;
inset:0;
z-index:-1;

```
background:
linear-gradient(
    180deg,
    rgba(255,255,255,.02),
    transparent
);
```

}

@keyframes auroraMove{

```
0%{
    transform:rotate(0deg) scale(1);
}

50%{
    transform:rotate(180deg) scale(1.15);
}

100%{
    transform:rotate(360deg) scale(1);
}
```

}

/* ==========================================================
HEADER
========================================================== */

.admin-header-title{
font-size:3rem;
font-weight:900;
letter-spacing:-0.05em;

```
background:
    linear-gradient(
        135deg,
        #ffffff,
        #8be9fd
    );

-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
```

}

.admin-header-sub{
font-size:.95rem;
color:var(--color-text-secondary);
}

.hero-header{
position:relative;
overflow:hidden;

```
border-radius:32px;

padding:2rem;

background:
linear-gradient(
    135deg,
    #7c3aed,
    #2563eb,
    #06b6d4
);

box-shadow:
    0 30px 60px rgba(37,99,235,.35);
```

}

.hero-header::before{
content:'';

```
position:absolute;

width:400px;
height:400px;

right:-100px;
top:-100px;

border-radius:50%;

background:
    rgba(255,255,255,.08);

animation:orbSpin 15s linear infinite;
```

}

@keyframes orbSpin{

```
from{
    transform:rotate(0);
}

to{
    transform:rotate(360deg);
}
```

}

/* ==========================================================
GLASS CARD
========================================================== */

.vcard{

```
background:
    rgba(255,255,255,.03);

backdrop-filter:
    blur(20px);

border:
    1px solid rgba(255,255,255,.08);

border-radius:
    24px;

box-shadow:
    var(--shadow-soft);
```

}

/* ==========================================================
STAT CARD
========================================================== */

.stat-card{
position:relative;
overflow:hidden;

```
border:none;

border-radius:24px;

padding:1.3rem;

transition:.35s ease;

color:white;
```

}

.stat-card::before{
content:'';
position:absolute;
inset:0;
}

.stat-card:nth-child(1)::before{
background:
linear-gradient(
135deg,
#f59e0b,
#f97316
);
}

.stat-card:nth-child(2)::before{
background:
linear-gradient(
135deg,
#2563eb,
#06b6d4
);
}

.stat-card:nth-child(3)::before{
background:
linear-gradient(
135deg,
#ec4899,
#f43f5e
);
}

.stat-card:nth-child(4)::before{
background:
linear-gradient(
135deg,
#10b981,
#22c55e
);
}

.stat-card:nth-child(5)::before{
background:
linear-gradient(
135deg,
#ef4444,
#dc2626
);
}

.stat-card>*{
position:relative;
z-index:2;
}

.stat-card:hover{
transform:
translateY(-8px)
scale(1.03);

```
box-shadow:
    0 25px 50px rgba(0,0,0,.25);
```

}

.stat-card.active{
outline:3px solid rgba(255,255,255,.35);
}

.stat-card .text-3xl{
color:white !important;
}

/* floating */

.stat-card{
animation:
floating 6s ease-in-out infinite;
}

.stat-card:nth-child(2){
animation-delay:.5s;
}

.stat-card:nth-child(3){
animation-delay:1s;
}

.stat-card:nth-child(4){
animation-delay:1.5s;
}

.stat-card:nth-child(5){
animation-delay:2s;
}

@keyframes floating{

```
0%,100%{
    transform:translateY(0);
}

50%{
    transform:translateY(-8px);
}
```

}

/* ==========================================================
TABS
========================================================== */

.tab-container{
display:flex;
gap:6px;

```
padding:8px;

border-radius:18px;

background:
    rgba(255,255,255,.04);

border:
    1px solid rgba(255,255,255,.08);

backdrop-filter:
    blur(18px);
```

}

.tab-item{
padding:.8rem 1rem;

```
border-radius:12px;

font-weight:700;

transition:.3s;
```

}

.tab-item:hover{
background:
rgba(255,255,255,.06);
}

.tab-item.active{
background:
linear-gradient(
135deg,
#7c3aed,
#2563eb
);

```
color:white;

box-shadow:
    0 10px 25px rgba(37,99,235,.3);
```

}

.tab-count{
background:white;
color:#111827;
}

/* ==========================================================
DATA ROW
========================================================== */

.data-row{
position:relative;

```
overflow:hidden;

border-radius:24px;

background:
    rgba(255,255,255,.03);

border:
    1px solid rgba(255,255,255,.08);

backdrop-filter:
    blur(20px);

transition:.35s ease;

opacity:0;

transform:
    translateY(30px);
```

}

.data-row.show{
opacity:1;
transform:
translateY(0);
}

.data-row:hover{

```
transform:
    translateY(-4px);

box-shadow:
    var(--shadow-hover);
```

}

.data-row::before{

```
content:'';

position:absolute;

inset:-2px;

background:
    linear-gradient(
        90deg,
        #7c3aed,
        #2563eb,
        #06b6d4,
        #22c55e,
        #f59e0b,
        #7c3aed
    );

background-size:
    300% 300%;

animation:
    borderMove 8s linear infinite;

opacity:0;

z-index:-1;
```

}

.data-row:hover::before{
opacity:1;
}

@keyframes borderMove{

```
0%{
    background-position:0% 50%;
}

100%{
    background-position:300% 50%;
}
```

}

/* ==========================================================
AVATAR
========================================================== */

.avatar-box{
width:64px;
height:64px;

```
border-radius:20px;

border:none;

box-shadow:
    0 10px 25px rgba(0,0,0,.18);
```

}

.avatar-initials{

```
background:
    linear-gradient(
        135deg,
        #7c3aed,
        #06b6d4
    );

color:white;

font-size:1rem;
font-weight:900;
```

}

/* ==========================================================
BADGE
========================================================== */

.status-badge{
border:none;

```
border-radius:999px;

font-size:.68rem;

padding:6px 14px;

font-weight:800;
```

}

.s-pending{
background:rgba(245,158,11,.15);
color:#fbbf24;
}

.s-under_review{
background:rgba(59,130,246,.15);
color:#60a5fa;
}

.s-need_revision{
background:rgba(236,72,153,.15);
color:#f472b6;
}

.s-approved{
background:rgba(16,185,129,.15);
color:#34d399;

```
animation:
    pulseGreen 2s infinite;
```

}

.s-rejected,
.s-suspended{
background:rgba(239,68,68,.15);
color:#f87171;
}

@keyframes pulseGreen{

```
0%{
    box-shadow:
        0 0 0 0 rgba(34,197,94,.5);
}

70%{
    box-shadow:
        0 0 0 14px rgba(34,197,94,0);
}

100%{
    box-shadow:
        0 0 0 0 rgba(34,197,94,0);
}
```

}

/* ==========================================================
BUTTON
========================================================== */

.btn-outline{
position:relative;
overflow:hidden;

```
border:none;

border-radius:14px;

padding:.75rem 1rem;

font-weight:700;

background:
    linear-gradient(
        135deg,
        rgba(124,58,237,.15),
        rgba(37,99,235,.15)
    );
```

}

.btn-outline::before{

```
content:'';

position:absolute;

width:50px;
height:250%;

left:-120px;
top:-50%;

transform:rotate(25deg);

background:
    rgba(255,255,255,.3);

transition:.8s;
```

}

.btn-outline:hover::before{
left:220%;
}

.btn-outline:hover{

```
background:
    linear-gradient(
        135deg,
        #7c3aed,
        #2563eb
    );

color:white;

transform:
    translateY(-2px);
```

}

/* ==========================================================
REJECTION
========================================================== */

.rejection-box{

```
background:
    linear-gradient(
        135deg,
        rgba(239,68,68,.08),
        rgba(239,68,68,.02)
    );

border-left:
    4px solid #ef4444;

border-radius:
    14px;
```

}

/* ==========================================================
SCROLLBAR
========================================================== */

::-webkit-scrollbar{
width:10px;
}

::-webkit-scrollbar-track{
background:transparent;
}

::-webkit-scrollbar-thumb{
border-radius:999px;

```
background:
    linear-gradient(
        #7c3aed,
        #2563eb
    );
```

}

</style>
@endpush

@section('content')
<div class="min-h-screen py-8 px-4">
<div class="mx-auto max-w-7xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-widest text-amber-400 font-bold mb-1">Admin Panel</p>
            <h1 class="text-3xl font-black text-white">Verifikasi Seller</h1>
            <p class="text-slate-400 text-sm mt-1">Kelola pengajuan, klarifikasi, dan status verifikasi penjual.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
        @foreach([
            ['pending', 'Pending', '#fbbf24', 'rgba(245,158,11,.1)', '⏳'],
            ['under_review', 'Direview', '#a5b4fc', 'rgba(99,102,241,.1)', '🔍'],
            ['need_revision', 'Perlu Revisi', '#fb923c', 'rgba(249,115,22,.1)', '✏️'],
            ['approved', 'Disetujui', '#34d399', 'rgba(16,185,129,.1)', '✅'],
            ['rejected', 'Ditolak/Suspend', '#f87171', 'rgba(239,68,68,.1)', '❌'],
        ] as [$key, $label, $color, $bg, $icon])
        <a href="?tab={{ $key }}" class="vcard p-4 {{ $tab === $key ? 'border-amber-500/40' : '' }}">
            <div class="text-xl mb-2">{{ $icon }}</div>
            <div class="text-2xl font-black text-white">{{ number_format($counts[$key]) }}</div>
            <div class="text-xs text-slate-400 mt-1">{{ $label }}</div>
        </a>
        @endforeach
    </div>

    {{-- Tabs --}}
    <div class="vcard p-1 flex gap-1 overflow-x-auto">
        @foreach([
            ['pending', '⏳ Pending', 'bg-amber-500/20 text-amber-300'],
            ['under_review', '🔍 Direview', 'bg-indigo-500/20 text-indigo-300'],
            ['need_revision', '✏️ Perlu Revisi', 'bg-orange-500/20 text-orange-300'],
            ['approved', '✅ Disetujui', 'bg-emerald-500/20 text-emerald-300'],
            ['rejected', '❌ Ditolak/Suspend', 'bg-red-500/20 text-red-300'],
        ] as [$key, $label, $cls])
        <a href="?tab={{ $key }}" class="tab-item {{ $tab === $key ? 'active' : '' }}">
            {{ $label }}
            @if($counts[$key] > 0)
            <span class="count-dot {{ $tab === $key ? 'bg-amber-500/30 text-amber-200' : 'bg-slate-700 text-slate-300' }}">
                {{ $counts[$key] }}
            </span>
            @endif
        </a>
        @endforeach
    </div>

    {{-- User List --}}
    @if($users->isEmpty())
    <div class="vcard p-16 text-center">
        <div class="text-5xl mb-4">🎉</div>
        <h3 class="text-white font-bold text-lg">Tidak ada data</h3>
        <p class="text-slate-400 text-sm mt-1">Belum ada pengajuan dengan status ini.</p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($users as $user)
        <div class="vcard p-5 hover:cursor-pointer" onclick="window.location='{{ route('admin.verification.show', $user) }}'">
            <div class="flex items-start gap-4">
                {{-- Avatar --}}
                <img src="{{ $user->shop_photo ? asset('storage/' . $user->shop_photo) : $user->avatar_url }}"
                     alt="{{ $user->name }}"
                     class="w-14 h-14 rounded-2xl object-cover shrink-0 border border-slate-700">

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h3 class="font-bold text-white text-base truncate">{{ $user->name }}</h3>
                        <span class="status-badge s-{{ $user->seller_status }}">
                            {{ match($user->seller_status) {
                                'pending'       => 'Pending',
                                'under_review'  => 'Direview',
                                'need_revision' => 'Perlu Revisi',
                                'approved'      => 'Disetujui',
                                'rejected'      => 'Ditolak',
                                'suspended'     => 'Suspend',
                                default         => ucfirst($user->seller_status),
                            } }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-400 truncate">{{ $user->email }}</p>
                    @if($user->shop_name)
                    <p class="text-sm font-medium text-slate-300 mt-1">🏪 {{ $user->shop_name }}</p>
                    @endif
                    @if($user->shop_description)
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $user->shop_description }}</p>
                    @endif
                </div>

                {{-- Date + Action --}}
                <div class="shrink-0 text-right">
                    <p class="text-xs text-slate-500">
                        {{ $user->created_at->diffForHumans() }}
                    </p>
                    <a href="{{ route('admin.verification.show', $user) }}"
                       class="mt-3 inline-flex items-center gap-1 px-4 py-2 rounded-xl bg-amber-500/15 text-amber-300 text-xs font-bold hover:bg-amber-500/25 transition-colors">
                        Review
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            {{-- Rejection reason if any --}}
            @if($user->seller_rejection_reason)
            <div class="mt-3 p-3 rounded-xl bg-red-900/20 border border-red-800/30">
                <p class="text-xs text-red-300"><span class="font-bold">Alasan:</span> {{ $user->seller_rejection_reason }}</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="flex justify-center pt-2">
        {{ $users->links() }}
    </div>
    @endif

</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',()=>{

    const observer = new IntersectionObserver(entries=>{

        entries.forEach(entry=>{

            if(entry.isIntersecting){
                entry.target.classList.add('show');
            }

        });

    },{threshold:.1});

    document
    .querySelectorAll('.data-row')
    .forEach(el=>observer.observe(el));

});
</script>
@endsection