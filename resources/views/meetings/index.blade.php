@extends('layouts.dashboard')

@section('title', 'Meetings')
@section('page-heading', 'Meetings')

@section('content')
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

    <div class="mb-6 flex items-center justify-between">
        <p class="text-slate-600">@if(auth()->user()->isAdmin())Create a meeting room and share the link so others can join.@else Join a meeting from the list below.@endif</p>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('meetings.create') }}" class="rounded-lg bg-amber-500 px-4 py-2 font-medium text-white transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
            Create Meeting
        </a>
        @endif
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        @if ($meetings->isEmpty())
        <div class="p-12 text-center text-slate-500">
            <p class="text-lg font-medium">No meetings yet.</p>
            <p class="mt-1 text-sm">@if(auth()->user()->isAdmin())Create a meeting to get started.@else No meetings are available to join.@endif</p>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('meetings.create') }}" class="mt-4 inline-block rounded-lg bg-amber-500 px-4 py-2 font-medium text-white hover:bg-amber-600">Create Meeting</a>
            @endif
        </div>
        @else
        <ul class="divide-y divide-slate-200">
            @foreach ($meetings as $meeting)
            <li class="flex flex-wrap items-center justify-between gap-4 p-4 sm:p-6">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="font-semibold text-slate-800 truncate">{{ $meeting->title }}</h3>
                        @if ($meeting->isEnded())
                            <span class="inline-flex items-center rounded-full bg-slate-200 px-2.5 py-0.5 text-xs font-medium text-slate-700">Ended</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">Active</span>
                        @endif
                        @if (auth()->user()->isAdmin())
                            @if ($meeting->isImmediate())
                                <span class="inline-flex items-center rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-medium text-sky-800">Immediate</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-violet-100 px-2.5 py-0.5 text-xs font-medium text-violet-800">Scheduled</span>
                            @endif
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-slate-500">Room: {{ $meeting->channel_name }}</p>
                    <p class="text-xs text-slate-400">Created by {{ $meeting->creator->name }} · {{ $meeting->created_at->diffForHumans() }}@if ($meeting->isScheduled()) · Starts <span class="scheduled-time-local" data-scheduled-utc="{{ $meeting->getScheduledAtUtcIso() }}" data-format="long"></span>@endif</p>
                    @if (auth()->user()->isAdmin() && $meeting->isScheduled() && $meeting->invitations->isNotEmpty())
                    <div class="mt-2 rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2">
                        <p class="text-xs font-medium text-slate-600">Invited users ({{ $meeting->invitations->count() }})</p>
                        <ul class="mt-1 space-y-0.5">
                            @foreach ($meeting->invitations as $invitation)
                            <li class="flex flex-wrap items-center gap-2 text-xs text-slate-700">
                                <span>{{ $invitation->user->name ?? '—' }}</span>
                                @if ($invitation->isPending())
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-1.5 py-0.5 text-[10px] font-medium text-amber-800">Pending</span>
                                @elseif ($invitation->isAccepted())
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-1.5 py-0.5 text-[10px] font-medium text-emerald-800">Accepted</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-200 px-1.5 py-0.5 text-[10px] font-medium text-slate-600">Rejected</span>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @elseif (auth()->user()->isAdmin() && $meeting->isScheduled())
                    <p class="mt-2 text-xs text-slate-500">No users invited yet.</p>
                    @endif
                </div>
                <div class="flex flex-shrink-0 items-center gap-2">
                    @if ($meeting->isEnded())
                        <span class="rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-sm font-medium text-slate-500 cursor-not-allowed">Ended</span>
                    @elseif (auth()->user()->isAdmin())
                        @php
                            $scheduledNotStartedAdmin = $meeting->isScheduled() && $meeting->scheduled_at && now()->startOfMinute()->lt($meeting->scheduled_at->copy()->startOfMinute());
                        @endphp
                        @if ($scheduledNotStartedAdmin)
                            <span class="rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-sm font-medium text-slate-500 scheduled-time-local" data-scheduled-utc="{{ $meeting->getScheduledAtUtcIso() }}" data-format="short" data-prefix="Starts "></span>
                        @else
                            <a href="{{ route('meetings.show', $meeting) }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-600">
                                Start meeting
                            </a>
                        @endif
                        @if ($meeting->isImmediate())
                        <button type="button" class="copy-meeting-link-btn rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50" data-meeting-url="{{ url(route('meetings.show', $meeting)) }}" title="Copy join link">
                            Copy link
                        </button>
                        @endif
                        @if ($meeting->isScheduled())
                        <button type="button" class="invite-meeting-btn rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50" data-meeting-id="{{ $meeting->id }}" data-meeting-title="{{ e($meeting->title) }}" data-inviteable-url="{{ route('meetings.inviteable-users', $meeting) }}" data-invite-url="{{ route('meetings.invite', $meeting) }}" title="Invite students">
                            Invite
                        </button>
                        @endif
                    @else
                        @php
                            $myInvitation = $meeting->invitations->first();
                            $isPending = $myInvitation && $myInvitation->isPending();
                            $scheduledNotStarted = $meeting->isScheduled() && $meeting->scheduled_at && now()->startOfMinute()->lt($meeting->scheduled_at->copy()->startOfMinute());
                        @endphp
                        @if ($isPending)
                            <button type="button" class="btn-accept-invitation rounded-lg bg-emerald-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-600" data-accept-url="{{ route('meetings.invitations.accept', $meeting) }}">
                                Accept
                            </button>
                            <button type="button" class="btn-reject-invitation rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50" data-reject-url="{{ route('meetings.invitations.reject', $meeting) }}">
                                Reject
                            </button>
                        @elseif ($scheduledNotStarted)
                            <span class="rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-sm font-medium text-slate-500 scheduled-time-local" data-scheduled-utc="{{ $meeting->getScheduledAtUtcIso() }}" data-format="short" data-prefix="Starts "></span>
                        @else
                            <a href="{{ route('meetings.show', $meeting) }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-600">
                                Join meeting
                            </a>
                        @endif
                    @endif
                </div>
            </li>
            @endforeach
        </ul>
        @endif
        @if ($meetings->hasPages())
        <div class="border-t border-slate-200 px-4 py-3">
            {{ $meetings->links() }}
        </div>
        @endif
    </div>

    @if (auth()->user()->isAdmin())
    {{-- Invite modal (admin only) --}}
    <div id="inviteModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/50" id="inviteModalBackdrop"></div>
        <div class="absolute left-1/2 top-1/2 w-full max-w-md -translate-x-1/2 -translate-y-1/2 rounded-xl bg-white p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-slate-800">Invite student</h3>
            <p id="inviteModalMeetingTitle" class="mt-1 text-sm text-slate-500"></p>
            <div class="mt-4">
                <label for="inviteUserSelect" class="block text-sm font-medium text-slate-700">Select user</label>
                <select id="inviteUserSelect" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                    <option value="">Loading…</option>
                </select>
            </div>
            <p id="inviteUserFeedback" class="mt-2 hidden text-sm"></p>
            <div class="mt-4 flex gap-2">
                <button type="button" id="inviteModalInviteBtn" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-600">
                    Invite
                </button>
                <button type="button" id="inviteModalClose" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif
@endsection

@if (auth()->user()->isAdmin())
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('inviteModal');
    var backdrop = document.getElementById('inviteModalBackdrop');
    var meetingTitleEl = document.getElementById('inviteModalMeetingTitle');
    var selectEl = document.getElementById('inviteUserSelect');
    var feedbackEl = document.getElementById('inviteUserFeedback');
    var inviteBtn = document.getElementById('inviteModalInviteBtn');
    var closeBtn = document.getElementById('inviteModalClose');
    var currentInviteableUrl = '';
    var currentInviteUrl = '';
    var csrfToken = @json(csrf_token());

    function showFeedback(msg, isError) {
        feedbackEl.textContent = msg;
        feedbackEl.classList.remove('hidden');
        feedbackEl.classList.toggle('text-red-600', isError);
        feedbackEl.classList.toggle('text-emerald-600', !isError);
    }

    document.querySelectorAll('.invite-meeting-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            currentInviteableUrl = this.getAttribute('data-inviteable-url');
            currentInviteUrl = this.getAttribute('data-invite-url');
            meetingTitleEl.textContent = this.getAttribute('data-meeting-title') || '';
            selectEl.innerHTML = '<option value="">Loading…</option>';
            feedbackEl.classList.add('hidden');
            modal.classList.remove('hidden');
            fetch(currentInviteableUrl, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(r) { return r.json(); })
                .then(function(users) {
                    selectEl.innerHTML = '<option value="">Select a user…</option>';
                    users.forEach(function(u) {
                        var opt = document.createElement('option');
                        opt.value = u.id;
                        opt.textContent = u.name + (u.email ? ' (' + u.email + ')' : '');
                        selectEl.appendChild(opt);
                    });
                })
                .catch(function() {
                    selectEl.innerHTML = '<option value="">Failed to load users</option>';
                });
        });
    });

    inviteBtn.addEventListener('click', function() {
        var uid = selectEl.value;
        if (!uid) return;
        inviteBtn.disabled = true;
        var fd = new FormData();
        fd.append('user_id', uid);
        fd.append('_token', csrfToken);
        fetch(currentInviteUrl, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
            body: fd
        }).then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); }).then(function(res) {
            if (res.ok) {
                showFeedback('User invited.', false);
                var opt = selectEl.querySelector('option[value="' + uid + '"]');
                if (opt) opt.remove();
                if (selectEl.options.length <= 1) selectEl.innerHTML = '<option value="">No more users to invite</option>';
            } else {
                showFeedback(res.data.error || 'Failed to invite.', true);
            }
            inviteBtn.disabled = false;
        }).catch(function() { inviteBtn.disabled = false; });
    });

    closeBtn.addEventListener('click', function() { modal.classList.add('hidden'); });
    if (backdrop) backdrop.addEventListener('click', function() { modal.classList.add('hidden'); });
});
</script>
@endpush
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var csrfToken = @json(csrf_token());

    // Render scheduled times in user's local time (data-scheduled-utc is UTC ISO string)
    document.querySelectorAll('.scheduled-time-local').forEach(function(el) {
        var utc = el.getAttribute('data-scheduled-utc');
        if (!utc) return;
        var d = new Date(utc);
        if (isNaN(d.getTime())) return;
        var format = el.getAttribute('data-format') || 'short';
        var prefix = el.getAttribute('data-prefix') || '';
        var shortStr = d.toLocaleDateString(undefined, { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' });
        var longStr = d.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' });
        el.textContent = prefix + (format === 'long' ? longStr : shortStr);
        el.title = prefix + longStr;
    });

    document.querySelectorAll('.copy-meeting-link-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var url = this.getAttribute('data-meeting-url');
            if (!url) return;
            navigator.clipboard.writeText(url).then(function() {
                var label = btn.textContent;
                btn.textContent = 'Copied!';
                setTimeout(function() { btn.textContent = label; }, 1500);
            });
        });
    });

    document.querySelectorAll('.btn-accept-invitation').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var url = this.getAttribute('data-accept-url');
            if (!url) return;
            var el = this;
            el.disabled = true;
            fetch(url, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ _token: csrfToken })
            }).then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); }).then(function(res) {
                if (res.ok) {
                    Swal.fire({ icon: 'success', title: 'Invitation accepted', text: 'You can join the meeting when it starts.' });
                    window.location.reload();
                } else {
                    el.disabled = false;
                    Swal.fire({ icon: 'error', title: 'Error', text: res.data.error || 'Could not accept invitation.' });
                }
            }).catch(function() {
                el.disabled = false;
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
            });
        });
    });

    document.querySelectorAll('.btn-reject-invitation').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var url = this.getAttribute('data-reject-url');
            if (!url) return;
            var el = this;
            el.disabled = true;
            fetch(url, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ _token: csrfToken })
            }).then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); }).then(function(res) {
                if (res.ok) {
                    Swal.fire({ icon: 'success', title: 'Invitation rejected' });
                    window.location.reload();
                } else {
                    el.disabled = false;
                    Swal.fire({ icon: 'error', title: 'Error', text: res.data.error || 'Could not reject invitation.' });
                }
            }).catch(function() {
                el.disabled = false;
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
            });
        });
    });
});
</script>
@endpush
