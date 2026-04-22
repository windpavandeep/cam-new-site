@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
@php
    $c = is_array($contact ?? null) ? $contact : [];
    $contact_email = isset($c['email']) ? trim((string) $c['email']) : '';
    $contact_phone = isset($c['phone']) ? trim((string) $c['phone']) : '';
    $contact_address = isset($c['address']) ? trim((string) $c['address']) : '';
    $contact_hours = isset($c['hours']) ? trim((string) $c['hours']) : '';
@endphp

<div class="container mx-auto px-4 py-10 md:py-14 max-w-4xl">
    <header class="mb-10">
        <p class="text-sm font-semibold uppercase tracking-wider text-amber-700 mb-2">Get in touch</p>
        <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-3">Contact Us</h1>
        <p class="text-slate-600 text-lg max-w-2xl">
            Send a message using the form below, or reach us through the details on this page. We aim to respond to enquiries as soon as we can.
        </p>
    </header>

    @if (session('success'))
    <div class="mb-8 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="mb-8 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid gap-8 lg:grid-cols-5 lg:gap-10">
        <aside class="lg:col-span-2 space-y-4 order-2 lg:order-1">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-4">Contact details</h2>
                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="font-medium text-slate-500 mb-1">Email</dt>
                        <dd class="text-slate-800">
                            @if ($contact_email !== '')
                                <a href="mailto:{{ $contact_email }}" class="text-amber-700 font-medium hover:underline break-all">{{ $contact_email }}</a>
                            @else
                                <span class="text-slate-500">Add a public email in <strong>Admin → Contact details</strong>.</span>
                            @endif
                        </dd>
                    </div>
                    @if ($contact_phone !== '')
                    <div>
                        <dt class="font-medium text-slate-500 mb-1">Phone</dt>
                        <dd><a href="tel:{{ preg_replace('/\s+/', '', $contact_phone) }}" class="text-slate-800 font-medium hover:text-amber-700">{{ $contact_phone }}</a></dd>
                    </div>
                    @endif
                    @if ($contact_address !== '')
                    <div>
                        <dt class="font-medium text-slate-500 mb-1">Address</dt>
                        <dd class="text-slate-800 whitespace-pre-line">{{ $contact_address }}</dd>
                    </div>
                    @endif
                    @if ($contact_hours !== '')
                    <div>
                        <dt class="font-medium text-slate-500 mb-1">Hours</dt>
                        <dd class="text-slate-800">{{ $contact_hours }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            <p class="text-xs text-slate-500 px-1">
                Every submission is stored in <strong>Admin → Messages</strong>.
                @if ($contact_email === '')
                    Set an inbox email in <strong>Contact details</strong> to also receive mail (configure <code class="bg-slate-100 px-0.5 rounded">.env</code> / mail as needed).
                @endif
            </p>
        </aside>

        <div class="lg:col-span-3 order-1 lg:order-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 md:p-8 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900 mb-6">Send a message</h2>
                <form method="POST" action="{{ route('contact.submit') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="100"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                            autocomplete="name">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required maxlength="255"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                            autocomplete="email">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="subject" class="mb-1 block text-sm font-medium text-slate-700">Subject <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}" maxlength="200"
                            placeholder="e.g. Question about a specific lesson"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                        @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="message" class="mb-1 block text-sm font-medium text-slate-700">Message</label>
                        <textarea name="message" id="message" rows="6" required maxlength="5000"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">{{ old('message') }}</textarea>
                        @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full sm:w-auto rounded-xl bg-amber-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        Send message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
