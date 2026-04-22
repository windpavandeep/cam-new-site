@extends('layouts.dashboard')

@section('title', 'Contact details')
@section('page-heading', 'Contact details')

@section('content')
    @if (session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
    @endif

    <div class="mb-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm max-w-2xl">
        <p class="text-sm text-slate-600 mb-6">
            These details appear on the public <strong>Contact</strong> page. The inbox email is also used when sending copies of form submissions via mail (if your server mail is configured).
        </p>

        <form method="POST" action="{{ route('dashboard.contact-settings.update') }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Public / inbox email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $settings->email) }}" maxlength="255"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                    placeholder="hello@example.com"
                    autocomplete="email">
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="phone" class="mb-1 block text-sm font-medium text-slate-700">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $settings->phone) }}" maxlength="50"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                    placeholder="+1 …">
                @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="address" class="mb-1 block text-sm font-medium text-slate-700">Address</label>
                <textarea name="address" id="address" rows="4" maxlength="2000"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">{{ old('address', $settings->address) }}</textarea>
                @error('address')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="hours" class="mb-1 block text-sm font-medium text-slate-700">Working hours</label>
                <textarea name="hours" id="hours" rows="3" maxlength="1000"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">{{ old('hours', $settings->hours) }}</textarea>
                @error('hours')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="rounded-lg bg-amber-500 px-4 py-2 font-medium text-white transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                Save
            </button>
        </form>
    </div>
@endsection
