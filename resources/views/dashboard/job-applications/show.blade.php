@extends('layouts.dashboard')

@section('title', 'Application')
@section('page-heading', 'Job application')

@section('content')
    <div class="mb-6">
        <a href="{{ route('dashboard.job-applications.index') }}" class="text-sm font-medium text-amber-700 hover:text-amber-800">← Back to applications</a>
    </div>

    @if (session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        {{ session('error') }}
    </div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm max-w-3xl space-y-5">
        <dl class="grid gap-4 sm:grid-cols-2 text-sm">
            <div>
                <dt class="font-medium text-slate-500 mb-1">Applied</dt>
                <dd class="text-slate-900">{{ $application->created_at->timezone(config('app.timezone'))->format('M j, Y g:i a') }}</dd>
            </div>
            <div>
                <dt class="font-medium text-slate-500 mb-1">Status</dt>
                <dd class="text-slate-900">
                    @if ($application->read_at === null)
                    <span class="inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">Unread</span>
                    @else
                    Read {{ $application->read_at->timezone(config('app.timezone'))->format('M j, Y g:i a') }}
                    @endif
                </dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="font-medium text-slate-500 mb-1">Job</dt>
                <dd class="text-slate-900">
                    @if ($application->job)
                    <a href="{{ route('dashboard.jobs.edit', $application->job) }}" class="text-amber-700 hover:underline">{{ $application->job->title }}</a>
                    @else
                    —
                    @endif
                </dd>
            </div>
            <div>
                <dt class="font-medium text-slate-500 mb-1">Name</dt>
                <dd class="text-slate-900">{{ $application->name }}</dd>
            </div>
            <div>
                <dt class="font-medium text-slate-500 mb-1">Email</dt>
                <dd class="break-all"><a href="mailto:{{ $application->email }}" class="text-amber-700 hover:underline">{{ $application->email }}</a></dd>
            </div>
            @if ($application->phone)
            <div class="sm:col-span-2">
                <dt class="font-medium text-slate-500 mb-1">Phone</dt>
                <dd class="text-slate-900">{{ $application->phone }}</dd>
            </div>
            @endif
        </dl>

        @if ($application->cover_letter)
        <div>
            <h2 class="text-sm font-medium text-slate-500 mb-2">Cover message</h2>
            <div class="rounded-lg border border-slate-100 bg-slate-50 px-4 py-3 text-slate-800 whitespace-pre-wrap">{{ $application->cover_letter }}</div>
        </div>
        @endif

        <div>
            <h2 class="text-sm font-medium text-slate-500 mb-2">CV</h2>
            <p class="text-sm text-slate-700 mb-3">{{ $application->cv_original_name ?? basename($application->cv_path) }}</p>
            <a href="{{ route('dashboard.job-applications.cv', $application) }}" class="inline-flex rounded-lg bg-sky-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                Download CV
            </a>
        </div>

        <form method="POST" action="{{ route('dashboard.job-applications.destroy', $application) }}" onsubmit="return confirm('Delete this application and CV file?');" class="pt-2 border-t border-slate-100">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                Delete application
            </button>
        </form>
    </div>
@endsection
