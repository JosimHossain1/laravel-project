@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    <section class="flex min-h-[60vh] flex-col items-center justify-center text-center">
        <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600 shadow-sm">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
            Laravel 13
        </span>

        <h1 class="mt-6 text-4xl font-bold tracking-tight sm:text-6xl">Welcome</h1>

        <p class="mt-4 max-w-md text-lg leading-relaxed text-slate-600">
            A simple starting point. Everything is set up and ready for your first feature.
        </p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-700">
                View products
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3"/>
                </svg>
            </a>

            <a href="https://laravel.com/docs" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                Documentation
            </a>
        </div>
    </section>
@endsection
