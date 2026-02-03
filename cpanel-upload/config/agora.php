<?php

declare(strict_types=1);

return [
    'app_id' => "3571a7071221470aa150144d6b7657ac",//env('AGORA_APP_ID', ''),
    'app_certificate' => "7f32690c42a94df6bd15229a18a69935",//env('AGORA_APP_CERTIFICATE', ''),
    'token_ttl_seconds' => (int) env('AGORA_TOKEN_TTL', 86400), // 24 hours max
];
