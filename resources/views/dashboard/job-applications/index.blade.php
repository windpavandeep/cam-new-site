@extends('layouts.dashboard')

@section('title', 'Job applications')
@section('page-heading', 'Job applications')

@section('content')
    @if (session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        @if ($applications->isEmpty())
        <div class="px-6 py-16 text-center text-sm text-slate-500">
            No applications yet.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Applied</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Job</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Candidate</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach ($applications as $row)
                    <tr class="{{ $row->read_at === null ? 'bg-amber-50/40' : '' }} hover:bg-slate-50/80">
                        <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                            {{ $row->created_at->timezone(config('app.timezone'))->format('M j, Y g:i a') }}
                        </td>
                        <td class="px-4 py-3 text-slate-800">
                            {{ $row->job?->title ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-900">{{ $row->name }}</div>
                            <div class="text-xs text-slate-500 break-all">{{ $row->email }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @if ($row->read_at === null)
                            <span class="inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">Unread</span>
                            @else
                            <span class="text-slate-500 text-xs">Read</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('dashboard.job-applications.show', $row) }}" class="font-medium text-amber-700 hover:text-amber-800">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-4 py-3">
            {{ $applications->links() }}
        </div>
        @endif
    </div>
@endsection
