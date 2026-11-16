@extends('layouts.app')

@section('title', 'Reset password')

@section('content')
    @php
        $input = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20';
    @endphp

    <div class="mx-auto w-full max-w-md">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-bold tracking-tight">Choose a new password</h1>
            <p class="mt-1 text-sm text-slate-500">Enter a new password for your account.</p>

            <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-5">
                @csrf

                {{-- The token and email come from the emailed link. --}}
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $email) }}"
                           required autocomplete="username" class="{{ $input }}">
                    @error('email')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">New password</label>
                    <input id="password" name="password" type="password"
                           required autofocus autocomplete="new-password" class="{{ $input }}">
                    <p class="mt-1.5 text-xs text-slate-500">At least 8 characters.</p>
                    @error('password')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700">
                        Confirm new password
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                           required autocomplete="new-password" class="{{ $input }}">
                </div>

                <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-700">
                    Reset password
                </button>
            </form>
        </div>
    </div>
@endsection
