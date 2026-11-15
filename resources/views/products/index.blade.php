@extends('layouts.app')

@section('title', 'Products')

@section('content')
    @php
        $totalProducts = $products->count();
        $totalStock = $products->sum('stock');
        $inventoryValue = $products->sum(fn ($product) => $product->price * $product->stock);
    @endphp

    {{-- Page header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Catalog</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight sm:text-3xl">Products</h1>
            <p class="mt-2 text-sm text-slate-600">Manage everything you sell, in one place.</p>
        </div>

        <a href="{{ route('products.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-700">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add product
        </a>
    </div>

    {{-- Stats --}}
    <div class="mt-8 grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total products</p>
            <p class="mt-2 text-3xl font-bold tracking-tight">{{ $totalProducts }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Units in stock</p>
            <p class="mt-2 text-3xl font-bold tracking-tight">{{ number_format($totalStock) }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Inventory value</p>
            <p class="mt-2 text-3xl font-bold tracking-tight">${{ number_format($inventoryValue, 2) }}</p>
        </div>
    </div>

    {{-- Table --}}
    <section class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        @if ($products->isEmpty())
            <div class="flex flex-col items-center justify-center px-6 py-20 text-center">
                <span class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                    </svg>
                </span>
                <h2 class="mt-4 text-base font-semibold">No products yet</h2>
                <p class="mt-1 max-w-sm text-sm text-slate-500">Add your first product and it will show up here.</p>
                <a href="{{ route('products.create') }}"
                   class="mt-6 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                    Add product
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-semibold">Product</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Price</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Stock</th>
                            <th scope="col" class="hidden px-6 py-3 font-semibold md:table-cell">Added</th>
                            <th scope="col" class="px-6 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach ($products as $product)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-indigo-500 to-sky-500 text-sm font-bold text-white shadow-sm">
                                            {{ strtoupper(substr($product->name, 0, 1)) }}
                                        </span>
                                        <div class="min-w-0">
                                            <a href="{{ route('products.show', $product) }}"
                                               class="block truncate font-medium text-slate-900 hover:text-indigo-600">
                                                {{ $product->name }}
                                            </a>
                                            <p class="mt-0.5 truncate text-xs text-slate-500">
                                                {{ $product->description ?: 'No description' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 font-semibold text-slate-900">
                                    ${{ number_format($product->price, 2) }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($product->stock <= 0)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                            Out of stock
                                        </span>
                                    @elseif ($product->stock <= 5)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            Low · {{ $product->stock }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            {{ $product->stock }} in stock
                                        </span>
                                    @endif
                                </td>

                                <td class="hidden px-6 py-4 text-slate-500 md:table-cell">
                                    {{ $product->created_at->format('M d, Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('products.show', $product) }}" title="View"
                                           class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                            </svg>
                                            <span class="sr-only">View</span>
                                        </a>

                                        <a href="{{ route('products.edit', $product) }}" title="Edit"
                                           class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                                            </svg>
                                            <span class="sr-only">Edit</span>
                                        </a>

                                        <form action="{{ route('products.destroy', $product) }}" method="POST"
                                              onsubmit="return confirm('Delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete"
                                                    class="rounded-lg p-2 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                                </svg>
                                                <span class="sr-only">Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between border-t border-slate-200 px-6 py-3 text-sm text-slate-500">
                <p>Showing <span class="font-medium text-slate-700">{{ $products->count() }}</span> {{ Str::plural('product', $products->count()) }}</p>
            </div>
        @endif
    </section>
@endsection
