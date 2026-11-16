@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mx-auto w-full max-w-6xl">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start">
            {{-- Sidebar --}}
            <aside class="w-full shrink-0 lg:sticky lg:top-24 lg:w-64">
                @include('partials.sidebar')
            </aside>

            {{-- Main content --}}
            <div class="min-w-0 flex-1 space-y-6">
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

                <div id="users" class="scroll-mt-24 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-2 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-bold tracking-tight">Users</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ $users->total() }} {{ Str::plural('user', $users->total()) }} registered.
                            </p>
                        </div>

                        @if ($users->hasPages())
                            <span class="w-fit rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
                            </span>
                        @endif
                    </div>

                    @if ($users->isEmpty())
                        <div class="px-6 py-12 text-center">
                            <p class="text-sm text-slate-500">No users yet.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-semibold">User</th>
                                        <th scope="col" class="px-6 py-3 font-semibold">Email</th>
                                        <th scope="col" class="px-6 py-3 font-semibold">Status</th>
                                        <th scope="col" class="px-6 py-3 font-semibold">Joined</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach ($users as $user)
                                        <tr @class(['bg-indigo-50/40' => $user->id === auth()->id()])>
                                            <td class="whitespace-nowrap px-6 py-4">
                                                <p class="font-semibold text-slate-900">
                                                    {{ $user->name }}

                                                    @if ($user->id === auth()->id())
                                                        <span class="ml-1 rounded-full bg-indigo-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-indigo-700">
                                                            You
                                                        </span>
                                                    @endif
                                                </p>
                                                <p class="mt-0.5 text-xs text-slate-500">ID #{{ $user->id }}</p>
                                            </td>

                                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                                {{ $user->email }}
                                            </td>

                                            <td class="whitespace-nowrap px-6 py-4">
                                                @if ($user->email_verified_at)
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                                        Verified
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                                        <span class="size-1.5 rounded-full bg-amber-500"></span>
                                                        Unverified
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                                {{ $user->created_at->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($users->hasPages())
                            <div class="border-t border-slate-200 px-6 py-4">
                                {{ $users->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
