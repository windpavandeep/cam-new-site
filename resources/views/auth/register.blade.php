@extends('layouts.app')

@section('title', 'Sign Up')

@section('content')
<div class="min-h-[calc(100vh-8rem)] flex items-center justify-center px-4 py-8">
    <div class="max-w-full" style="width: 500px;">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-xl font-bold text-slate-800 mb-4">Sign Up</h1>

            @if ($errors->any())
            <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Your name"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                </div>
                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                </div>
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required autocomplete="new-password" placeholder="At least 8 characters"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                </div>
                <div class="mt-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" placeholder="Same as above"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                </div>

                <div class="pt-2 mt-8">
                    <button type="submit" class="block w-full rounded border-2 border-amber-500 bg-amber-500 px-4 py-3 font-medium text-white hover:bg-amber-600 hover:border-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        Create Account
                    </button>
                </div>
            </form>

            <p class="mt-4 pt-4 text-center text-sm text-slate-600 border-t border-slate-100">
                Already have an account? <a href="{{ route('login') }}" class="font-medium text-amber-500 hover:text-amber-600">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection