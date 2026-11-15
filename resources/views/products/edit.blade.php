@extends('layouts.app')

@section('title', 'Edit ' . $product->name)

@section('content')
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('products.index') }}" class="transition hover:text-slate-900">Products</a>
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
        </svg>
        <span class="font-medium text-slate-700">Edit</span>
    </nav>

    <div class="mt-4 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">Edit product</h1>
            <p class="mt-2 text-sm text-slate-600">Update the details of “{{ $product->name }}”.</p>
        </div>

        <a href="{{ route('products.show', $product) }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
            View product
        </a>
    </div>

    <form action="{{ route('products.update', $product) }}" method="POST" class="mt-8">
        @csrf
        @method('PUT')
        @include('products._form', ['product' => $product])
    </form>
@endsection
