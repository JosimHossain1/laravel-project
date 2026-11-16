@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mx-auto w-full max-w-3xl">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-600">Dashboard</p>

            <h1 class="mt-2 text-2xl font-bold tracking-tight">
                Hello, {{ auth()->user()->name }}
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                You are signed in as <span class="font-medium text-slate-700">{{ auth()->user()->email }}</span>.
            </p>

            <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl bg-slate-50 p-4">
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">User ID</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900">{{ auth()->id() }}</dd>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Session driver</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900">{{ config('session.driver') }}</dd>
                </div>
            </dl>

            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Log out
                </button>
            </form>
        </div>
    </div>
@endsection