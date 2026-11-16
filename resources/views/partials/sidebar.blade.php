@php
    $link = 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition';
    $active = 'bg-indigo-50 text-indigo-700';
    $inactive = 'text-slate-600 hover:bg-slate-100 hover:text-slate-900';

    $isDashboard = request()->routeIs('dashboard');
    $isProducts = request()->routeIs('products.index', 'products.show', 'products.edit');
    $isCreate = request()->routeIs('products.create');
    $isHome = request()->routeIs('home');
@endphp

<div class="space-y-4">
    {{-- Signed-in user --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <span class="grid size-10 shrink-0 place-items-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700">
                {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
            </span>

            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-slate-900">
                    {{ auth()->user()->name }}
                </p>
                <p class="truncate text-xs text-slate-500">
                    {{ auth()->user()->email }}
                </p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
        <p class="px-3 pb-2 pt-1 text-xs font-semibold uppercase tracking-widest text-slate-400">
            Overview
        </p>

        <nav class="space-y-1">
            <a href="{{ route('dashboard') }}"
               @class([$link, $active => $isDashboard, $inactive => ! $isDashboard])>
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Zm9.75 0a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 8.25 20.25H6A2.25 2.25 0 0 1 3.75 18v-2.25Zm9.75 0a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                </svg>
                Dashboard
            </a>
        </nav>

        <p class="px-3 pb-2 pt-5 text-xs font-semibold uppercase tracking-widest text-slate-400">
            Manage
        </p>

        <nav class="space-y-1">
            <a href="{{ route('products.index') }}"
               @class([$link, $active => $isProducts, $inactive => ! $isProducts])>
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                </svg>
                Products
            </a>

            <a href="{{ route('products.create') }}"
               @class([$link, $active => $isCreate, $inactive => ! $isCreate])>
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add product
            </a>

            <a href="{{ route('dashboard') }}#users" @class([$link, $inactive])>
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                </svg>
                Users
            </a>
        </nav>

        <div class="my-3 border-t border-slate-200"></div>

        <nav class="space-y-1">
            <a href="{{ route('home') }}"
               @class([$link, $active => $isHome, $inactive => ! $isHome])>
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Back to site
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="{{ $link }} w-full text-rose-600 hover:bg-rose-50">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                    </svg>
                    Log out
                </button>
            </form>
        </nav>
    </div>
</div>
