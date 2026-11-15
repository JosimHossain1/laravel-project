@extends('layouts.app')

@section('title', 'New product')

@section('content')
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('products.index') }}" class="transition hover:text-slate-900">Products</a>
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
        </svg>
        <span class="font-medium text-slate-700">New</span>
    </nav>

    <div class="mt-4">
        <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">New product</h1>
        <p class="mt-2 text-sm text-slate-600">Add something new to your catalog.</p>
    </div>

    <form action="{{ route('products.store') }}" method="POST" class="mt-8">
        @csrf
        @include('products._form', ['product' => null])
    </form>
@endsection
