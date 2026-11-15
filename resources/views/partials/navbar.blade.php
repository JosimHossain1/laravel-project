<header class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/80 backdrop-blur-xl">
    <nav class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-3 px-4 sm:px-6">
        {{-- Brand / logo --}}
        <a href="/" class="flex shrink-0 items-center gap-2.5">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-violet-500 via-indigo-500 to-sky-500 text-white shadow-sm shadow-indigo-500/25">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"/>
                </svg>
            </span>
            <span class="hidden text-base font-bold tracking-tight sm:inline">
                Cute <span class="text-indigo-600">Laravel</span>
            </span>
        </a>

        {{-- Links --}}
        <div class="flex items-center gap-1">
            <a href="/"
               @class([
                   'rounded-lg px-2.5 py-2 text-sm font-medium transition sm:px-3',
                   'bg-indigo-50 text-indigo-700' => request()->is('/'),
                   'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->is('/'),
               ])>
                Home
            </a>

            <a href="{{ route('products.index') }}"
               @class([
                   'rounded-lg px-2.5 py-2 text-sm font-medium transition sm:px-3',
                   'bg-indigo-50 text-indigo-700' => request()->routeIs('products.*'),
                   'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->routeIs('products.*'),
               ])>
                Products
            </a>
        </div>

        {{-- Action --}}
        <a href="{{ route('products.create') }}"
           class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-700 sm:px-4">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            <span class="hidden sm:inline">New product</span>
            <span class="sr-only sm:hidden">New product</span>
        </a>
    </nav>
</header>
