<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingFeedback;
use App\Models\MeetingInvitation;
use App\Models\MeetingParticipant;
use App\Models\User;
use App\Services\AgoraTokenService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MeetingController extends Controller
{
    public function __construct(
        private AgoraTokenService $agoraTokenService
    ) {}

    public function index(): View
    {
        $query = Meeting::query()->with('creator')->orderByDesc('created_at');

        if (auth()->user()->isAdmin()) {
            // Admins see all meetings.
        } else {
            // Students see only meetings they are invited to (pending/accepted) or have participated in.
            $query->where(function ($q) {
                $q->whereHas('invitations', fn($q2) => $q2->where('user_id', auth()->id())->whereIn('status', [MeetingInvitation::STATUS_PENDING, MeetingInvitation::STATUS_ACCEPTED]))
                    ->orWhereHas('participants', fn($q2) => $q2->where('user_id', auth()->id()));
            });
            // Eager-load current user's invitation per meeting so Accept/Reject and status show correctly.
            $query->with(['invitations' => fn($q) => $q->where('user_id', auth()->id())]);
        }

        $meetings = $query->paginate(15);

        if (auth()->user()->isAdmin()) {
            // For admins, eager-load invited users so we can show the invited list per meeting.
            $meetings->load(['invitations.user']);
        }

        return view('meetings.index', ['meetings' => $meetings]);
    }

    public function create(): View|RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            return redirect()->route('meetings.index')->with('error', 'Only admins can create meetings.');
        }

        return view('meetings.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            return redirect()->route('meetings.index')->with('error', 'Only admins can create meetings.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:immediate,scheduled'],
            'scheduled_at' => ['nullable', 'required_if:type,scheduled', 'date'],
            'user_timezone' => ['nullable', 'string', 'max:50'],
        ]);

        $channelName = Str::lower(Str::random(12));
        while (Meeting::where('channel_name', $channelName)->exists()) {
            $channelName = Str::lower(Str::random(12));
        }

        $scheduledAt = null;
        if (($validated['type'] ?? '') === 'scheduled' && ! empty($validated['scheduled_at'] ?? null)) {
            $tz = $validated['user_timezone'] ?? config('app.timezone');
            try {
                $scheduledAt = Carbon::parse($validated['scheduled_at'], $tz)->setTimezone('UTC');
                if ($scheduledAt->isPast()) {
                    return redirect()->back()->withInput()->withErrors(['scheduled_at' => 'The scheduled time must be in the future.']);
                }
            } catch (\Exception $e) {
                $scheduledAt = Carbon::parse($validated['scheduled_at'], config('app.timezone'))->setTimezone('UTC');
            }
        }

        Meeting::create([
            'title' => $validated['title'],
            'channel_name' => $channelName,
            'created_by' => auth()->id(),
            'scheduled_at' => $scheduledAt,
        ]);

        return redirect()->route('meetings.index')->with('success', 'Meeting created.');
    }

    public function show(Meeting $meeting): View|RedirectResponse
    {
        if ($meeting->isEnded()) {
            return redirect()->route('meetings.index')->with('error', 'This meeting has ended.');
        }

        // Scheduled: students may only join if invited (accepted) or participant, and only after scheduled time.
        if ($meeting->isScheduled() && now()->startOfMinute()->lt($meeting->scheduled_at->copy()->startOfMinute())) {
            return redirect()->route('meetings.index')->with('error', 'This meeting has not started yet. It is scheduled for ' . $meeting->scheduled_at->format('M j, Y \a\t g:i A') . '.');
        }

        if (! auth()->user()->isAdmin() && ! $meeting->isHost((int) auth()->id())) {
            if ($meeting->isScheduled()) {
                // Scheduled: only invited (accepted) or participant.
                $invitation = $meeting->invitations()->where('user_id', auth()->id())->first();
                $participant = $meeting->participants()->where('user_id', auth()->id())->exists();
                $canJoin = $participant || ($invitation && $invitation->isAccepted());
                if (! $canJoin) {
                    return redirect()->route('meetings.index')->with('error', $invitation && $invitation->isPending() ? 'Please accept the invitation first.' : 'You are not invited to this meeting.');
                }
            }
            // Immediate: anyone with link can join (no invite check).
        }

        $appId = config('agora.app_id');
        if (empty($appId)) {
            return redirect()->route('meetings.index')->with('error', 'Agora is not configured.');
        }

        return view('meetings.join', [
            'meeting' => $meeting,
            'appId' => $appId,
            'currentUserName' => auth()->user()->name,
            'isHost' => $meeting->isHost((int) auth()->id()),
            'isScheduled' => $meeting->isScheduled(),
        ]);
    }

    public function status(Meeting $meeting): JsonResponse
    {
        return response()->json([
            'ended' => $meeting->isEnded(),
        ]);
    }

    public function end(Meeting $meeting): JsonResponse
    {
        if (! $meeting->isHost((int) auth()->id())) {
            return response()->json(['error' => 'Only the host can end the meeting.'], 403);
        }
        $meeting->ended_at = now();
        $meeting->save();

        return response()->json(['ok' => true]);
    }

    public function participantNames(Request $request, Meeting $meeting): JsonResponse
    {
        $validated = $request->validate([
            'uids' => ['required', 'array'],
            'uids.*' => ['string'],
        ]);
        $uids = array_map('intval', array_filter($validated['uids'], fn($v) => is_numeric($v)));
        $names = User::query()
            ->whereIn('id', $uids)
            ->get()
            ->mapWithKeys(fn(User $u) => [(string) $u->id => $u->name])
            ->all();

        return response()->json($names);
    }

    public function token(Meeting $meeting): JsonResponse
    {
        if ($meeting->isScheduled() && now()->startOfMinute()->lt($meeting->scheduled_at->copy()->startOfMinute())) {
            return response()->json(['error' => 'This meeting has not started yet. It is scheduled for ' . $meeting->scheduled_at->format('M j, Y \a\t g:i A') . '.'], 403);
        }

        $token = $this->agoraTokenService->generateRtcToken($meeting, auth()->user());
        if ($token === '') {
            return response()->json(['error' => 'Failed to generate token.'], 500);
        }

        // Record that this user joined (so they see the meeting in their list).
        MeetingParticipant::firstOrCreate(
            ['meeting_id' => $meeting->id, 'user_id' => auth()->id()],
            ['joined_at' => now()]
        );

        return response()->json([
            'token' => $token,
            'uid' => (string) auth()->id(),
            'channel' => $meeting->channel_name,
        ]);
    }

    public function storeFeedback(Request $request, Meeting $meeting): JsonResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        MeetingFeedback::updateOrCreate(
            [
                'meeting_id' => $meeting->id,
                'user_id' => auth()->id(),
            ],
            [
                'rating' => (int) $validated['rating'],
                'feedback' => $validated['feedback'] ?? null,
            ]
        );

        return response()->json(['ok' => true]);
    }

    public function invite(Request $request, Meeting $meeting): JsonResponse|RedirectResponse
    {
        if (! $meeting->isHost((int) auth()->id()) && ! auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Only the host or an admin can invite users.'], 403);
        }
        if ($meeting->isEnded()) {
            return response()->json(['error' => 'This meeting has ended.'], 422);
        }
        if ($meeting->isImmediate()) {
            return response()->json(['error' => 'Only scheduled meetings can have invited users. Immediate meetings are public; share the join link.'], 422);
        }

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);
        $user_id = (int) $validated['user_id'];
        if ($user_id === (int) $meeting->created_by) {
            return response()->json(['error' => 'The host is already in the meeting.'], 422);
        }

        MeetingInvitation::firstOrCreate(
            ['meeting_id' => $meeting->id, 'user_id' => $user_id],
            ['status' => MeetingInvitation::STATUS_PENDING]
        );

        return response()->json(['ok' => true]);
    }

    public function acceptInvitation(Meeting $meeting): JsonResponse
    {
        $invitation = $meeting->invitations()->where('user_id', auth()->id())->first();
        if (! $invitation || ! $invitation->isPending()) {
            return response()->json(['error' => 'No pending invitation found.'], 422);
        }
        $invitation->status = MeetingInvitation::STATUS_ACCEPTED;
        $invitation->save();
        return response()->json(['ok' => true]);
    }

    public function rejectInvitation(Meeting $meeting): JsonResponse
    {
        $invitation = $meeting->invitations()->where('user_id', auth()->id())->first();
        if (! $invitation || ! $invitation->isPending()) {
            return response()->json(['error' => 'No pending invitation found.'], 422);
        }
        $invitation->status = MeetingInvitation::STATUS_REJECTED;
        $invitation->save();
        return response()->json(['ok' => true]);
    }

    public function inviteableUsers(Meeting $meeting): JsonResponse
    {
        if (! $meeting->isHost((int) auth()->id()) && ! auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Forbidden.'], 403);
        }
        if ($meeting->isImmediate()) {
            return response()->json(['error' => 'Only scheduled meetings can have invited users.'], 422);
        }
        $invited_ids = $meeting->invitations()->pluck('user_id')->push($meeting->created_by)->all();
        $users = User::query()
            ->whereNotIn('id', $invited_ids)
            ->orderBy('name')
            ->get(['id', 'name', 'email'])
            ->map(fn(User $u) => ['id' => $u->id, 'name' => $u->name, 'email' => $u->email])
            ->values()
            ->all();
        return response()->json($users);
    }
}
