@php($product = $product ?? null)

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Main fields --}}
    <div class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
        <div>
            <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">
                Product name <span class="text-rose-500">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name', $product?->name) }}"
                   placeholder="e.g. Wireless Keyboard"
                   @class([
                       'w-full rounded-xl border px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2',
                       'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' => $errors->has('name'),
                       'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' => ! $errors->has('name'),
                   ])>

            @error('name')
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="mb-1.5 block text-sm font-medium text-slate-700">Description</label>
            <textarea id="description" name="description" rows="4"
                      placeholder="Short details about this product..."
                      @class([
                          'w-full rounded-xl border px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2',
                          'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' => $errors->has('description'),
                          'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' => ! $errors->has('description'),
                      ])>{{ old('description', $product?->description) }}</textarea>

            @error('description')
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="price" class="mb-1.5 block text-sm font-medium text-slate-700">
                    Price <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-400">$</span>
                    <input type="number" id="price" name="price" step="0.01" min="0"
                           value="{{ old('price', $product?->price) }}" placeholder="0.00"
                           @class([
                               'w-full rounded-xl border py-2.5 pl-8 pr-4 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2',
                               'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' => $errors->has('price'),
                               'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' => ! $errors->has('price'),
                           ])>
                </div>

                @error('price')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="stock" class="mb-1.5 block text-sm font-medium text-slate-700">
                    Stock <span class="text-rose-500">*</span>
                </label>
                <input type="number" id="stock" name="stock" min="0"
                       value="{{ old('stock', $product?->stock ?? 0) }}" placeholder="0"
                       @class([
                           'w-full rounded-xl border px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2',
                           'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' => $errors->has('stock'),
                           'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' => ! $errors->has('stock'),
                       ])>

                @error('stock')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 pt-5">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-700">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                </svg>
                {{ $product ? 'Save changes' : 'Create product' }}
            </button>

            <a href="{{ route('products.index') }}"
               class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                Cancel
            </a>
        </div>
    </div>

    {{-- Side tips --}}
    <aside class="space-y-4">
        <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-5">
            <h2 class="text-sm font-semibold text-indigo-900">Good to know</h2>
            <ul class="mt-3 space-y-2.5 text-sm text-indigo-800">
                <li class="flex gap-2">
                    <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-indigo-400"></span>
                    Name, price and stock are required.
                </li>
                <li class="flex gap-2">
                    <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-indigo-400"></span>
                    Stock below 5 is shown as “Low” in the list.
                </li>
                <li class="flex gap-2">
                    <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-indigo-400"></span>
                    Price is stored with two decimals.
                </li>
            </ul>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-sm font-semibold">Preview</h2>
            <div class="mt-3 flex items-center gap-3">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-indigo-500 to-sky-500 text-sm font-bold text-white">
                    {{ strtoupper(substr(old('name', $product?->name ?? 'P'), 0, 1)) }}
                </span>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-slate-900">{{ old('name', $product?->name ?? 'Product name') }}</p>
                    <p class="text-xs text-slate-500">${{ number_format((float) old('price', $product?->price ?? 0), 2) }}</p>
                </div>
            </div>
        </div>
    </aside>
</div>
