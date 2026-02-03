<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Meeting;
use App\Models\User;
use TaylanUnutmaz\AgoraTokenBuilder\RtcTokenBuilder;

class AgoraTokenService
{
    public function generateRtcToken(Meeting $meeting, User $user, int $ttlSeconds = 0): string
    {
        $appId = config('agora.app_id');
        $appCert = config('agora.app_certificate');
        if (empty($appId) || empty($appCert)) {
            return '';
        }
        $ttlSeconds = $ttlSeconds > 0 ? $ttlSeconds : config('agora.token_ttl_seconds', 86400);
        $privilegeExpiredTs = time() + $ttlSeconds;
        $userAccount = (string) $user->id;

        return RtcTokenBuilder::buildTokenWithUserAccount(
            $appId,
            $appCert,
            $meeting->channel_name,
            $userAccount,
            RtcTokenBuilder::RolePublisher,
            $privilegeExpiredTs
        );
    }
}
