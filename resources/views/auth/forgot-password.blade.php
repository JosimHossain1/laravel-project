@extends('layouts.app')

@section('title', 'Forgot password')

@section('content')
    @php
        $input = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20';
    @endphp

    <div class="mx-auto w-full max-w-md">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-bold tracking-tight">Forgot your password?</h1>
            <p class="mt-1 text-sm text-slate-500">
                Enter your email address and we will send you a secure reset link.
            </p>

            <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}"
                           required autofocus autocomplete="username" class="{{ $input }}">
                    @error('email')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-700">
                    Email password reset link
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500">
                Remembered it?
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-700">
                    Back to login
                </a>
            </p>
        </div>
    </div>
@endsection
