<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    protected $fillable = [
        'title',
        'channel_name',
        'created_by',
        'scheduled_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime', // stored as UTC in DB; app timezone should be UTC for correct comparison
            'ended_at' => 'datetime',
        ];
    }

    /** Return scheduled_at as UTC ISO 8601 string for frontend (display in user's local time). */
    public function getScheduledAtUtcIso(): ?string
    {
        return $this->scheduled_at?->copy()->utc()->format('Y-m-d\TH:i:s\Z');
    }

    public function isEnded(): bool
    {
        return $this->ended_at !== null;
    }

    public function isScheduled(): bool
    {
        return $this->scheduled_at !== null;
    }

    public function isImmediate(): bool
    {
        return $this->scheduled_at === null;
    }

    public function isHost(int $userId): bool
    {
        return (int) $this->created_by === $userId;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(MeetingInvitation::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    public function getChannelName(): string
    {
        return $this->channel_name;
    }
}
