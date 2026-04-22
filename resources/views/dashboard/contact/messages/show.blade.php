@extends('layouts.dashboard')

@section('title', 'Message')
@section('page-heading', 'Contact message')

@section('content')
    <div class="mb-6">
        <a href="{{ route('dashboard.contact-messages.index') }}" class="text-sm font-medium text-amber-700 hover:text-amber-800">← Back to messages</a>
    </div>

    @if (session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm max-w-3xl space-y-5">
        <dl class="grid gap-4 sm:grid-cols-2 text-sm">
            <div>
                <dt class="font-medium text-slate-500 mb-1">Received</dt>
                <dd class="text-slate-900">{{ $message->created_at->timezone(config('app.timezone'))->format('M j, Y g:i a') }}</dd>
            </div>
            <div>
                <dt class="font-medium text-slate-500 mb-1">Status</dt>
                <dd class="text-slate-900">
                    @if ($message->read_at === null)
                    <span class="inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">Unread</span>
                    @else
                    Read {{ $message->read_at->timezone(config('app.timezone'))->format('M j, Y g:i a') }}
                    @endif
                </dd>
            </div>
            <div>
                <dt class="font-medium text-slate-500 mb-1">Name</dt>
                <dd class="text-slate-900">{{ $message->name }}</dd>
            </div>
            <div>
                <dt class="font-medium text-slate-500 mb-1">Email</dt>
                <dd class="break-all"><a href="mailto:{{ $message->email }}" class="text-amber-700 hover:underline">{{ $message->email }}</a></dd>
            </div>
            @if ($message->subject)
            <div class="sm:col-span-2">
                <dt class="font-medium text-slate-500 mb-1">Subject</dt>
                <dd class="text-slate-900">{{ $message->subject }}</dd>
            </div>
            @endif
        </dl>
        <div>
            <h2 class="text-sm font-medium text-slate-500 mb-2">Message</h2>
            <div class="rounded-lg border border-slate-100 bg-slate-50 px-4 py-3 text-slate-800 whitespace-pre-wrap">{{ $message->message }}</div>
        </div>

        <form method="POST" action="{{ route('dashboard.contact-messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?');" class="pt-2 border-t border-slate-100">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                Delete message
            </button>
        </form>
    </div>
@endsection
