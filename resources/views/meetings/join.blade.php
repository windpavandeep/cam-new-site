@extends('layouts.dashboard')

@section('title', $meeting->title)
@section('page-heading', $meeting->title)

@section('content')
<div class="flex flex-col flex-1 min-h-0 gap-4">
    <p class="text-slate-600 flex-shrink-0">Allow camera and microphone when prompted. Use the controls below to manage video, audio, screen share, and recording.</p>

        <div id="meetingContainer" class="relative flex-1 min-h-[400px] w-full rounded-xl border border-slate-200 bg-slate-900 overflow-hidden">
            <div id="localVideoWrapper" class="absolute bottom-4 right-4 z-10 w-72 rounded-lg overflow-hidden border-2 border-white shadow-lg bg-slate-800 hidden">
                <div id="localPlayer" class="aspect-video w-full h-full bg-slate-800"></div>
                <p id="localPlayerLabel" class="bg-slate-800/80 px-2 py-1 text-xs text-white truncate">{{ auth()->user()->name }}</p>
            </div>
            <div id="remoteVideos" class="absolute inset-0 flex flex-wrap gap-4 p-4 items-center justify-center content-center">
            <p id="waitingMsg" class="text-slate-400">Connecting…</p>
        </div>
        <div id="recordingIndicator" class="absolute top-4 left-4 z-10 hidden flex items-center gap-2 rounded-full bg-red-600 px-3 py-1.5 text-sm font-medium text-white">
            <span class="h-2 w-2 animate-pulse rounded-full bg-white"></span>
            Recording
        </div>
    </div>

    {{-- Control bar --}}
    <div id="controlBar" class="flex flex-wrap items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white p-3 shadow-sm hidden">
        <button type="button" id="btnVideo" class="meeting-btn flex items-center gap-2 rounded-lg px-4 py-2.5 font-medium transition" title="Turn camera off">
            <svg id="iconVideoOn" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <svg id="iconVideoOff" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
            <span id="labelVideo">Video</span>
        </button>
        <button type="button" id="btnAudio" class="meeting-btn flex items-center gap-2 rounded-lg px-4 py-2.5 font-medium transition" title="Mute microphone">
            <svg id="iconAudioOn" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
            </svg>
            <svg id="iconAudioOff" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 8.023 12 8.959 12 10v4c0 1.041-.877 1.976-1.707 2.307L5.586 15z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
            </svg>
            <span id="labelAudio">Mute</span>
        </button>
        <button type="button" id="btnScreenShare" class="meeting-btn flex items-center gap-2 rounded-lg px-4 py-2.5 font-medium transition" title="Share screen">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span id="labelScreenShare">Share screen</span>
        </button>
        <button type="button" id="btnRecord" class="meeting-btn flex items-center gap-2 rounded-lg px-4 py-2.5 font-medium transition" title="Start recording">
            <svg id="iconRecordStart" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="6" />
            </svg>
            <svg id="iconRecordStop" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="6" y="6" width="12" height="12" rx="2" stroke-width="2" />
            </svg>
            <span id="labelRecord">Record</span>
        </button>
        <button type="button" id="btnInvite" class="meeting-btn flex items-center gap-2 rounded-lg px-4 py-2.5 font-medium transition" title="Invite others">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            Invite
        </button>
        <button type="button" id="leaveBtn" class="flex items-center gap-2 rounded-lg bg-red-500 px-4 py-2.5 font-medium text-white transition hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Leave
        </button>
    </div>

    {{-- Left meeting: rating & feedback (hidden until user leaves) --}}
    <div id="leftMeetingPanel" class="hidden flex-1 flex flex-col items-center justify-center rounded-xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-8 shadow-sm">
        <p id="leftMeetingMessage" class="text-lg font-medium text-slate-700"></p>
        <div id="feedbackSection" class="mt-6 w-full max-w-md">
            <p class="text-center text-sm font-medium text-slate-600">How was the meeting?</p>
            <div id="ratingStars" class="mt-2 flex justify-center gap-1">
                @for ($i = 1; $i <= 5; $i++)
                    <button type="button" class="rating-star rounded p-1 text-2xl text-slate-300 transition hover:text-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-1" data-rating="{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}" aria-label="Rate {{ $i }} star{{ $i > 1 ? 's' : '' }}">
                        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    </button>
                @endfor
            </div>
            <textarea id="feedbackText" class="mt-4 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500" rows="3" placeholder="Optional: share your feedback…" maxlength="2000"></textarea>
            <p class="mt-1 text-right text-xs text-slate-400"><span id="feedbackCharCount">0</span>/2000</p>
            <div class="mt-4 flex flex-wrap justify-center gap-3">
                <button type="button" id="btnSubmitFeedback" class="rounded-lg bg-amber-500 px-5 py-2.5 font-medium text-white transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                    Submit feedback
                </button>
                <button type="button" id="btnSkipFeedback" class="rounded-lg border border-slate-300 px-5 py-2.5 font-medium text-slate-600 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2">
                    Skip
                </button>
            </div>
        </div>
        <div id="feedbackThankYou" class="mt-6 hidden text-center">
            <p class="text-lg font-medium text-slate-700">Thank you for your feedback!</p>
            <a href="{{ route('meetings.index') }}" class="mt-3 inline-block rounded-lg bg-amber-500 px-4 py-2 font-medium text-white transition hover:bg-amber-600">Back to meetings</a>
        </div>
        <div id="feedbackSkipped" class="mt-6 hidden text-center">
            <p class="text-lg font-medium text-slate-700">Thanks!</p>
            <a href="{{ route('meetings.index') }}" class="mt-3 inline-block rounded-lg border border-slate-300 px-4 py-2 font-medium text-slate-700 transition hover:bg-slate-50">Back to meetings</a>
        </div>
    </div>

    <div class="flex items-center gap-3 flex-shrink-0">
        <a href="{{ route('meetings.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 font-medium text-slate-700 transition hover:bg-slate-50">
            Back to meetings
        </a>
    </div>
</div>

{{-- Invite modal: link share for immediate, invite-by-user for scheduled --}}
<div id="inviteModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" id="inviteModalBackdrop"></div>
    <div class="absolute left-1/2 top-1/2 w-full max-w-md -translate-x-1/2 -translate-y-1/2 rounded-xl bg-white p-6 shadow-xl">
        <h3 class="text-lg font-semibold text-slate-800">Invite others</h3>
        @if($isScheduled ?? false)
            <p class="mt-1 text-sm text-slate-500">This meeting is by invitation only. Invite users below; they will see it on their Meetings page.</p>
            @if($isHost ?? false)
            <div id="inviteByUserSection" class="mt-4">
                <p class="text-sm font-medium text-slate-700">Invite user to this meeting</p>
                <p class="mt-1 text-sm text-slate-500">Invited users will see this meeting on their Meetings page and can accept or reject.</p>
                <div class="mt-3 flex gap-2">
                    <select id="inviteUserSelect" class="flex-1 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                        <option value="">Select a user…</option>
                    </select>
                    <button type="button" id="btnInviteUser" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-600">Invite</button>
                </div>
                <p id="inviteUserFeedback" class="mt-2 hidden text-sm"></p>
            </div>
            @endif
        @else
            <p class="mt-1 text-sm text-slate-500">Share this link so others can join the meeting. They must be logged in.</p>
            <div id="inviteLinkSection" class="mt-4 flex gap-2">
                <input type="text" id="inviteLinkInput" readonly class="flex-1 rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-700">
                <button type="button" id="btnCopyLink" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-600">
                    Copy link
                </button>
            </div>
            <p id="copyFeedback" class="mt-2 hidden text-sm text-emerald-600">Link copied to clipboard.</p>
            <div class="mt-4 border-t border-slate-200 pt-4">
                <p class="text-sm font-medium text-slate-700">Invite from platform</p>
                <p class="mt-1 text-sm text-slate-500">Share the link above with other users. They can join when logged in.</p>
            </div>
        @endif
        <button type="button" id="btnCloseInvite" class="mt-4 w-full rounded-lg border border-slate-300 px-4 py-2 font-medium text-slate-700 transition hover:bg-slate-50">
            Close
        </button>
    </div>
</div>

@push('styles')
<style>
    .meeting-btn {
        background: #f1f5f9;
        color: #334155;
    }

    .meeting-btn:hover {
        background: #e2e8f0;
    }

    .meeting-btn.active {
        background: #f59e0b;
        color: #fff;
    }

    .meeting-btn.danger {
        background: #ef4444;
        color: #fff;
    }
</style>
@endpush
@push('scripts')
<script>
    (function() {
        var appId = @json($appId);
        var meetingChannel = @json($meeting->channel_name);
        var tokenUrl = @json(route('meetings.token', $meeting));
        var statusUrl = @json(route('meetings.status', $meeting));
        var endUrl = @json(route('meetings.end', $meeting));
        var feedbackUrl = @json(route('meetings.feedback', $meeting));
        var inviteableUsersUrl = @json(route('meetings.inviteable-users', $meeting));
        var inviteUrl = @json(route('meetings.invite', $meeting));
        var participantNamesUrl = @json(route('meetings.participant-names', $meeting));
        var inviteLink = @json(request()->url());
        var isHost = @json($isHost ?? false);
        var csrfToken = @json(csrf_token());
        var remoteUids = [];
        var statusPollInterval = null;
        var localVideoWrapper = document.getElementById('localVideoWrapper');
        var localPlayer = document.getElementById('localPlayer');
        var remoteVideos = document.getElementById('remoteVideos');
        var waitingMsg = document.getElementById('waitingMsg');
        var leaveBtn = document.getElementById('leaveBtn');
        var controlBar = document.getElementById('controlBar');
        var recordingIndicator = document.getElementById('recordingIndicator');
        var client = null;
        var localTracks = {
            video: null,
            audio: null
        };
        var screenTrack = null;
        var mediaRecorder = null;
        var recordedChunks = [];

        function showError(msg) {
            waitingMsg.textContent = msg;
            waitingMsg.classList.remove('hidden');
        }

        function getOrCreateRemoteWrapper(uid, label) {
            var wrapper = document.getElementById('remote-' + uid);
            if (wrapper) return wrapper;
            wrapper = document.createElement('div');
            wrapper.id = 'remote-' + uid;
            wrapper.className = 'relative flex-1 min-w-[280px] max-w-[calc(100%-1rem)] rounded-lg overflow-hidden border-2 border-slate-600 bg-slate-700 aspect-video';
            wrapper.innerHTML = '<div class="aspect-video w-full relative"><div class="remote-video-container absolute inset-0 bg-slate-800" data-uid="' + uid + '"></div><div class="remote-placeholder absolute inset-0 flex items-center justify-center bg-slate-700 hidden" data-uid="' + uid + '"><div class="flex flex-col items-center gap-2 text-slate-400"><svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg><span class="text-xs font-medium">Camera off</span></div></div></div><p class="absolute bottom-0 left-0 right-0 bg-slate-800/80 px-2 py-1 text-xs text-white truncate">' + (label || 'User ' + uid) + '</p>';
            waitingMsg.classList.add('hidden');
            remoteVideos.appendChild(wrapper);
            return wrapper;
        }

        function getRemoteVideoContainer(uid) {
            var wrapper = document.getElementById('remote-' + uid);
            return wrapper ? wrapper.querySelector('.remote-video-container') : null;
        }

        function showRemotePlaceholder(uid) {
            var wrapper = document.getElementById('remote-' + uid);
            if (!wrapper) return;
            var container = wrapper.querySelector('.remote-video-container');
            var placeholder = wrapper.querySelector('.remote-placeholder');
            if (container) {
                container.innerHTML = '';
                container.classList.add('hidden');
            }
            if (placeholder) placeholder.classList.remove('hidden');
        }

        function showRemoteVideo(uid) {
            var wrapper = document.getElementById('remote-' + uid);
            if (!wrapper) return;
            var container = wrapper.querySelector('.remote-video-container');
            var placeholder = wrapper.querySelector('.remote-placeholder');
            if (container) container.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        }

        function removeRemotePlayer(uid) {
            var el = document.getElementById('remote-' + uid);
            if (el) el.remove();
            remoteUids = remoteUids.filter(function(id) { return id !== uid; });
        }

        function updateRemoteLabel(uid, name) {
            var wrapper = document.getElementById('remote-' + uid);
            if (!wrapper) return;
            var label = wrapper.querySelector('p');
            if (label) label.textContent = name || ('User ' + uid);
        }

        function fetchParticipantNames() {
            if (remoteUids.length === 0) return;
            var params = new URLSearchParams();
            remoteUids.forEach(function(id) { params.append('uids[]', id); });
            fetch(participantNamesUrl + '?' + params.toString(), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            }).then(function(r) { return r.json(); }).then(function(names) {
                Object.keys(names).forEach(function(uid) {
                    updateRemoteLabel(uid, names[uid]);
                });
            }).catch(function() {});
        }

        function showControls() {
            controlBar.classList.remove('hidden');
        }

        var leftMeetingPanel = document.getElementById('leftMeetingPanel');
        var leftMeetingMessage = document.getElementById('leftMeetingMessage');
        var feedbackSection = document.getElementById('feedbackSection');
        var feedbackThankYou = document.getElementById('feedbackThankYou');
        var feedbackSkipped = document.getElementById('feedbackSkipped');
        var meetingContainer = document.getElementById('meetingContainer');
        var selectedRating = 0;

        function showLeftMeetingPanel(endedByHost) {
            meetingContainer.classList.add('hidden');
            leftMeetingPanel.classList.remove('hidden');
            leftMeetingMessage.textContent = endedByHost ? 'Meeting ended by host.' : 'You left the meeting.';
            feedbackSection.classList.remove('hidden');
            feedbackThankYou.classList.add('hidden');
            feedbackSkipped.classList.add('hidden');
            selectedRating = 0;
            document.getElementById('feedbackText').value = '';
            document.getElementById('feedbackCharCount').textContent = '0';
            updateRatingStars();
        }

        function updateRatingStars() {
            var stars = document.querySelectorAll('#ratingStars .rating-star');
            stars.forEach(function(btn, i) {
                var value = parseInt(btn.getAttribute('data-rating'), 10);
                btn.classList.toggle('text-amber-500', value <= selectedRating);
                btn.classList.toggle('text-slate-300', value > selectedRating);
            });
        }

        function leaveChannel(endedByHost) {
            if (statusPollInterval) {
                clearInterval(statusPollInterval);
                statusPollInterval = null;
            }
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
                mediaRecorder = null;
            }
            if (screenTrack) {
                screenTrack.close();
                screenTrack = null;
            }
            if (localTracks.video) {
                localTracks.video.close();
                localTracks.video = null;
            }
            if (localTracks.audio) {
                localTracks.audio.close();
                localTracks.audio = null;
            }
            if (client) {
                client.leave();
                client = null;
            }
            localVideoWrapper.classList.add('hidden');
            controlBar.classList.add('hidden');
            leaveBtn.classList.add('hidden');
            recordingIndicator.classList.add('hidden');
            showLeftMeetingPanel(endedByHost);
        }

        leaveBtn.addEventListener('click', function() {
            if (isHost) {
                fetch(endUrl, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }
                }).then(function() { leaveChannel(false); }).catch(function() { leaveChannel(false); });
            } else {
                leaveChannel(false);
            }
        });

        document.querySelectorAll('#ratingStars .rating-star').forEach(function(btn) {
            btn.addEventListener('click', function() {
                selectedRating = parseInt(this.getAttribute('data-rating'), 10);
                updateRatingStars();
            });
        });
        document.getElementById('feedbackText').addEventListener('input', function() {
            document.getElementById('feedbackCharCount').textContent = this.value.length;
        });
        document.getElementById('btnSubmitFeedback').addEventListener('click', function() {
            if (selectedRating < 1 || selectedRating > 5) {
                alert('Please select a rating (1–5 stars).');
                return;
            }
            var btn = this;
            btn.disabled = true;
            var body = new FormData();
            body.append('rating', selectedRating);
            body.append('feedback', document.getElementById('feedbackText').value);
            body.append('_token', csrfToken);
            fetch(feedbackUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                body: body
            }).then(function(r) {
                if (!r.ok) throw new Error('Failed to save feedback');
                return r.json();
            }).then(function() {
                feedbackSection.classList.add('hidden');
                feedbackThankYou.classList.remove('hidden');
            }).catch(function() {
                alert('Could not save feedback. Please try again.');
                btn.disabled = false;
            });
        });
        document.getElementById('btnSkipFeedback').addEventListener('click', function() {
            feedbackSection.classList.add('hidden');
            feedbackSkipped.classList.remove('hidden');
        });

        async function joinMeeting() {
            var AgoraRTC = window.AgoraRTC;
            if (!AgoraRTC) {
                showError('Agora SDK failed to load. Check your connection and try again.');
                return;
            }
            try {
                var resp = await fetch(tokenUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                var data = await resp.json();
                if (!resp.ok) {
                    showError(data.error || 'Failed to get token.');
                    return;
                }
                var token = data.token;
                var uid = data.uid;
                var channel = data.channel || meetingChannel;

                client = AgoraRTC.createClient({
                    mode: 'rtc',
                    codec: 'vp8'
                });
                client.on('user-published', async function(user, mediaType) {
                    await client.subscribe(user, mediaType);
                    var uid = String(user.uid);
                    if (remoteUids.indexOf(uid) === -1) remoteUids.push(uid);
                    if (mediaType === 'video') {
                        getOrCreateRemoteWrapper(uid, '…');
                        var container = getRemoteVideoContainer(uid);
                        if (container) {
                            showRemoteVideo(uid);
                            user.videoTrack.play(container);
                        }
                        fetchParticipantNames();
                    }
                    if (mediaType === 'audio') {
                        getOrCreateRemoteWrapper(uid, '…');
                        showRemotePlaceholder(uid);
                        user.audioTrack.play();
                        fetchParticipantNames();
                    }
                });
                client.on('user-unpublished', function(user, mediaType) {
                    if (mediaType === 'video') {
                        showRemotePlaceholder(String(user.uid));
                    }
                });
                client.on('user-left', function(user) {
                    removeRemotePlayer(String(user.uid));
                });

                await client.join(appId, channel, token, uid);

                localTracks.audio = await AgoraRTC.createMicrophoneAudioTrack();
                localTracks.video = await AgoraRTC.createCameraVideoTrack();
                await client.publish([localTracks.audio, localTracks.video]);
                localTracks.video.play(localPlayer);
                localVideoWrapper.classList.remove('hidden');
                leaveBtn.classList.remove('hidden');
                showControls();
                waitingMsg.textContent = 'Waiting for others…';

                statusPollInterval = setInterval(function() {
                    fetch(statusUrl, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(function(r) { return r.json(); })
                        .then(function(data) {
                            if (data.ended) {
                                if (statusPollInterval) clearInterval(statusPollInterval);
                                statusPollInterval = null;
                                leaveChannel(true);
                            }
                        })
                        .catch(function() {});
                }, 3000);

                // Video toggle
                document.getElementById('btnVideo').addEventListener('click', function() {
                    if (!localTracks.video) return;
                    var enabled = !localTracks.video.enabled;
                    localTracks.video.setEnabled(enabled);
                    document.getElementById('iconVideoOn').classList.toggle('hidden', !enabled);
                    document.getElementById('iconVideoOff').classList.toggle('hidden', enabled);
                    document.getElementById('labelVideo').textContent = enabled ? 'Video' : 'Video off';
                });

                // Audio toggle
                document.getElementById('btnAudio').addEventListener('click', function() {
                    if (!localTracks.audio) return;
                    var enabled = !localTracks.audio.enabled;
                    localTracks.audio.setEnabled(enabled);
                    document.getElementById('iconAudioOn').classList.toggle('hidden', !enabled);
                    document.getElementById('iconAudioOff').classList.toggle('hidden', enabled);
                    document.getElementById('labelAudio').textContent = enabled ? 'Mute' : 'Unmute';
                });

                // Screen share
                document.getElementById('btnScreenShare').addEventListener('click', async function() {
                    var btn = document.getElementById('btnScreenShare');
                    var label = document.getElementById('labelScreenShare');
                    if (screenTrack) {
                        await client.unpublish([screenTrack]);
                        screenTrack.close();
                        screenTrack = null;
                        await client.publish([localTracks.video]);
                        localTracks.video.play(localPlayer);
                        localVideoWrapper.classList.remove('hidden');
                        btn.classList.remove('active');
                        label.textContent = 'Share screen';
                        return;
                    }
                    try {
                        screenTrack = await AgoraRTC.createScreenVideoTrack({
                            encoderConfig: '1080p_2'
                        });
                        await client.unpublish([localTracks.video]);
                        localVideoWrapper.classList.add('hidden');
                        await client.publish([screenTrack]);
                        screenTrack.play(localPlayer);
                        localVideoWrapper.classList.remove('hidden');
                        localVideoWrapper.querySelector('#localPlayerLabel').textContent = 'Screen';
                        btn.classList.add('active');
                        label.textContent = 'Stop share';
                        screenTrack.on('track-ended', function() {
                            client.unpublish([screenTrack]).then(function() {
                                screenTrack.close();
                                screenTrack = null;
                                client.publish([localTracks.video]);
                                localTracks.video.play(localPlayer);
                                localVideoWrapper.querySelector('#localPlayerLabel').textContent = 'You';
                                btn.classList.remove('active');
                                label.textContent = 'Share screen';
                            });
                        });
                    } catch (e) {
                        console.error(e);
                        if (e.message && e.message.toLowerCase().indexOf('cancel') !== -1) return;
                        alert('Screen share failed: ' + (e.message || 'Unknown error'));
                    }
                });

                // Recording (client-side: local camera + mic)
                document.getElementById('btnRecord').addEventListener('click', function() {
                    var btn = document.getElementById('btnRecord');
                    var label = document.getElementById('labelRecord');
                    var iconStart = document.getElementById('iconRecordStart');
                    var iconStop = document.getElementById('iconRecordStop');
                    if (mediaRecorder && mediaRecorder.state === 'recording') {
                        mediaRecorder.stop();
                        return;
                    }
                    try {
                        var stream = new MediaStream();
                        if (localTracks.video && localTracks.video.getMediaStreamTrack) stream.addTrack(localTracks.video.getMediaStreamTrack());
                        if (localTracks.audio && localTracks.audio.getMediaStreamTrack) stream.addTrack(localTracks.audio.getMediaStreamTrack());
                        if (stream.getTracks().length === 0) {
                            alert('No tracks to record.');
                            return;
                        }
                        var options = {
                            mimeType: 'video/webm;codecs=vp9,opus',
                            videoBitsPerSecond: 2500000,
                            audioBitsPerSecond: 128000
                        };
                        if (!MediaRecorder.isTypeSupported(options.mimeType)) options = {
                            mimeType: 'video/webm',
                            videoBitsPerSecond: 2500000,
                            audioBitsPerSecond: 128000
                        };
                        mediaRecorder = new MediaRecorder(stream, options);
                        recordedChunks = [];
                        mediaRecorder.ondataavailable = function(e) {
                            if (e.data.size > 0) recordedChunks.push(e.data);
                        };
                        mediaRecorder.onstop = function() {
                            var blob = new Blob(recordedChunks, {
                                type: 'video/webm'
                            });
                            var url = URL.createObjectURL(blob);
                            var a = document.createElement('a');
                            a.href = url;
                            a.download = 'meeting-recording-' + Date.now() + '.webm';
                            a.click();
                            URL.revokeObjectURL(url);
                            recordingIndicator.classList.add('hidden');
                            btn.classList.remove('danger');
                            label.textContent = 'Record';
                            iconStart.classList.remove('hidden');
                            iconStop.classList.add('hidden');
                        };
                        mediaRecorder.start(1000);
                        recordingIndicator.classList.remove('hidden');
                        btn.classList.add('danger');
                        label.textContent = 'Stop';
                        iconStart.classList.add('hidden');
                        iconStop.classList.remove('hidden');
                    } catch (e) {
                        console.error(e);
                        alert('Recording failed: ' + (e.message || 'Unknown error'));
                    }
                });

                // Invite modal
                document.getElementById('btnInvite').addEventListener('click', function() {
                    var inviteLinkInput = document.getElementById('inviteLinkInput');
                    if (inviteLinkInput) inviteLinkInput.value = inviteLink;
                    var copyFeedback = document.getElementById('copyFeedback');
                    if (copyFeedback) copyFeedback.classList.add('hidden');
                    var inviteUserFeedback = document.getElementById('inviteUserFeedback');
                    if (inviteUserFeedback) inviteUserFeedback.classList.add('hidden');
                    document.getElementById('inviteModal').classList.remove('hidden');
                    if (isHost && document.getElementById('inviteUserSelect')) {
                        fetch(inviteableUsersUrl, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(function(r) { return r.json(); })
                            .then(function(users) {
                                var sel = document.getElementById('inviteUserSelect');
                                sel.innerHTML = '<option value="">Select a user…</option>';
                                users.forEach(function(u) {
                                    var opt = document.createElement('option');
                                    opt.value = u.id;
                                    opt.textContent = u.name + (u.email ? ' (' + u.email + ')' : '');
                                    sel.appendChild(opt);
                                });
                            })
                            .catch(function() {});
                    }
                });
                if (document.getElementById('btnInviteUser')) {
                    document.getElementById('btnInviteUser').addEventListener('click', function() {
                        var sel = document.getElementById('inviteUserSelect');
                        var uid = sel && sel.value ? sel.value : '';
                        if (!uid) return;
                        var btn = this;
                        btn.disabled = true;
                        var fd = new FormData();
                        fd.append('user_id', uid);
                        fd.append('_token', csrfToken);
                        fetch(inviteUrl, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                            body: fd
                        }).then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); }).then(function(res) {
                            if (res.ok) {
                                var fb = document.getElementById('inviteUserFeedback');
                                if (fb) { fb.textContent = 'User invited.'; fb.classList.remove('hidden'); fb.classList.add('text-emerald-600'); fb.classList.remove('text-red-600'); }
                                if (sel) sel.innerHTML = '<option value="">Select a user…</option>';
                            } else {
                                var fb = document.getElementById('inviteUserFeedback');
                                if (fb) { fb.textContent = res.data.error || 'Failed to invite.'; fb.classList.remove('hidden'); fb.classList.add('text-red-600'); fb.classList.remove('text-emerald-600'); }
                            }
                            btn.disabled = false;
                        }).catch(function() { btn.disabled = false; });
                    });
                }
                if (document.getElementById('btnCopyLink')) {
                    document.getElementById('btnCopyLink').addEventListener('click', function() {
                        var input = document.getElementById('inviteLinkInput');
                        if (!input) return;
                        input.select();
                        input.setSelectionRange(0, 99999);
                        navigator.clipboard.writeText(inviteLink).then(function() {
                            var fb = document.getElementById('copyFeedback');
                            if (fb) fb.classList.remove('hidden');
                        });
                    });
                }
                document.getElementById('btnCloseInvite').addEventListener('click', function() {
                    document.getElementById('inviteModal').classList.add('hidden');
                });
                document.getElementById('inviteModalBackdrop').addEventListener('click', function() {
                    document.getElementById('inviteModal').classList.add('hidden');
                });
            } catch (err) {
                console.error(err);
                showError(err.message || 'Failed to join meeting.');
            }
        }

        function runWhenSdkReady() {
            if (window.AgoraRTC) {
                joinMeeting();
                return;
            }
            showError('Agora SDK failed to load. Check your connection and try again.');
        }

        var s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/agora-rtc-sdk-ng@4.24.0/AgoraRTC_N-production.js';
        s.crossOrigin = 'anonymous';
        s.onload = runWhenSdkReady;
        s.onerror = function() {
            showError('Agora SDK failed to load. Check your connection and try again.');
        };
        document.head.appendChild(s);
    })();
</script>
@endpush
@endsection