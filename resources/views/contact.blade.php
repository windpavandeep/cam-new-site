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

<div class="container mx-auto max-w-5xl px-4 py-10 md:py-14">
    <header class="mb-10 rounded-2xl border border-sky-200 bg-gradient-to-br from-sky-50 via-white to-teal-50 px-6 py-7 md:px-8 md:py-9">
        <p class="mb-2 text-xs font-semibold uppercase tracking-[0.24em] text-sky-600">Get in touch</p>
        <h1 class="mb-3 text-3xl font-bold text-slate-800 md:text-4xl">Contact Us</h1>
        <p class="max-w-2xl text-lg text-slate-600">
            Send a message using the form below, or reach us through the details on this page. We aim to respond to enquiries as soon as we can.
        </p>
    </header>

    @if (session('success'))
    <div class="mb-8 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="mb-8 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid gap-8 lg:grid-cols-5 lg:gap-10">
        <aside class="lg:col-span-2 space-y-4 order-2 lg:order-1">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/70">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-sky-700">Contact details</h2>
                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="mb-1 font-medium text-slate-500">Email</dt>
                        <dd class="text-slate-700">
                            @if ($contact_email !== '')
                                <a href="mailto:{{ $contact_email }}" class="break-all font-medium text-sky-700 hover:underline">{{ $contact_email }}</a>
                            @else
                                <span class="text-slate-500">Add a public email in <strong>Admin → Contact details</strong>.</span>
                            @endif
                        </dd>
                    </div>
                    @if ($contact_phone !== '')
                    <div>
                        <dt class="mb-1 font-medium text-slate-500">Phone</dt>
                        <dd><a href="tel:{{ preg_replace('/\s+/', '', $contact_phone) }}" class="font-medium text-slate-700 hover:text-sky-700">{{ $contact_phone }}</a></dd>
                    </div>
                    @endif
                    @if ($contact_address !== '')
                    <div>
                        <dt class="mb-1 font-medium text-slate-500">Address</dt>
                        <dd class="whitespace-pre-line text-slate-700">{{ $contact_address }}</dd>
                    </div>
                    @endif
                    @if ($contact_hours !== '')
                    <div>
                        <dt class="mb-1 font-medium text-slate-500">Hours</dt>
                        <dd class="text-slate-700">{{ $contact_hours }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            <p class="px-1 text-xs text-slate-500">
                Every submission is stored in <strong>Admin → Messages</strong>.
                @if ($contact_email === '')
                    Set an inbox email in <strong>Contact details</strong> to also receive mail (configure <code class="rounded bg-slate-100 px-0.5 text-slate-600">.env</code> / mail as needed).
                @endif
            </p>
        </aside>

        <div class="lg:col-span-3 order-1 lg:order-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/70 md:p-8">
                <h2 class="mb-6 text-lg font-semibold text-slate-800">Send a message</h2>
                <form method="POST" action="{{ route('contact.submit') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="100"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500"
                            autocomplete="name">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required maxlength="255"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500"
                            autocomplete="email">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="subject" class="mb-1 block text-sm font-medium text-slate-700">Subject <span class="font-normal text-slate-500">(optional)</span></label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}" maxlength="200"
                            placeholder="e.g. Question about a specific lesson"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                        @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="message" class="mb-1 block text-sm font-medium text-slate-700">Message</label>
                        <textarea name="message" id="message" rows="6" required maxlength="5000"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">{{ old('message') }}</textarea>
                        @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-sky-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-white sm:w-auto">
                        Send message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
