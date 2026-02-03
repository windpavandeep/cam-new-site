@extends('layouts.dashboard')

@section('title', 'Create Meeting')
@section('page-heading', 'Create Meeting')

@section('content')
    <div class="max-w-xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('meetings.store') }}" class="space-y-4" id="createMeetingForm">
            @csrf
            <input type="hidden" name="user_timezone" id="user_timezone" value="">
            <div>
                <label for="title" class="mb-1 block text-sm font-medium text-slate-700">Meeting title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    placeholder="e.g. CNC Training Session"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <p class="mb-2 block text-sm font-medium text-slate-700">Meeting type</p>
                <div class="flex gap-4">
                    <label class="inline-flex cursor-pointer items-center gap-2">
                        <input type="radio" name="type" value="immediate" {{ old('type', 'immediate') === 'immediate' ? 'checked' : '' }} class="text-amber-500 focus:ring-amber-500">
                        <span class="text-sm text-slate-700">Immediate</span>
                    </label>
                    <label class="inline-flex cursor-pointer items-center gap-2">
                        <input type="radio" name="type" value="scheduled" {{ old('type') === 'scheduled' ? 'checked' : '' }} class="text-amber-500 focus:ring-amber-500">
                        <span class="text-sm text-slate-700">Scheduled</span>
                    </label>
                </div>
                <p class="mt-1 text-xs text-slate-500">Immediate: public meeting, share via link. Scheduled: invite-only, date & time required.</p>
            </div>

            <div id="scheduledFields" class="hidden space-y-2">
                <div>
                    <label for="scheduled_at" class="mb-1 block text-sm font-medium text-slate-700">Date & time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" value="{{ old('scheduled_at') }}"
                        min="{{ now()->format('Y-m-d\TH:i') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                    @error('scheduled_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <p class="text-xs text-slate-500">Students can only join from this time. Invite users from the meeting list.</p>
            </div>

            <p class="text-sm text-slate-500" id="typeHint">Immediate meetings are public: share the join link. A unique room name will be generated.</p>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-amber-500 px-4 py-2 font-medium text-white transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                    Create Meeting
                </button>
                <a href="{{ route('meetings.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 font-medium text-slate-700 transition hover:bg-slate-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var typeRadios = document.querySelectorAll('input[name="type"]');
        var scheduledFields = document.getElementById('scheduledFields');
        var scheduledInput = document.getElementById('scheduled_at');
        var typeHint = document.getElementById('typeHint');
        function updateType() {
            var type = document.querySelector('input[name="type"]:checked');
            var isScheduled = type && type.value === 'scheduled';
            scheduledFields.classList.toggle('hidden', !isScheduled);
            scheduledInput.required = isScheduled;
            typeHint.textContent = isScheduled ? 'Scheduled meetings are invite-only. Invite users from the meeting list after creating.' : 'Immediate meetings are public: share the join link. A unique room name will be generated.';
        }
        typeRadios.forEach(function(r) { r.addEventListener('change', updateType); });
        updateType();
        try {
            var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
            if (tz && document.getElementById('user_timezone')) document.getElementById('user_timezone').value = tz;
        } catch (e) {}
    });
    </script>
    @endpush
@endsection
