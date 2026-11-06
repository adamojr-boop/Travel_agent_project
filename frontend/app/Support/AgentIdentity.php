<?php

namespace App\Support;

use RuntimeException;

class AgentIdentity
{
    public static function issue(int $userId): string
    {
        $secret = config('services.internal_api.token');
        if (! is_string($secret) || $secret === '') {
            throw new RuntimeException('INTERNAL_API_TOKEN non configurato.');
        }
        $payload = $userId.'.'.(time() + 1800);
        return $payload.'.'.hash_hmac('sha256', $payload, $secret);
    }

    public static function verify(?string $token): ?int
    {
        $secret = config('services.internal_api.token');
        if (! is_string($secret) || $secret === '' || ! is_string($token)) {
            return null;
        }
        $parts = explode('.', $token);
        if (count($parts) !== 3 || ! ctype_digit($parts[0]) || ! ctype_digit($parts[1])
            || (int) $parts[0] < 1 || (int) $parts[1] < time()
            || (int) $parts[1] > time() + 1800) {
            return null;
        }
        $payload = $parts[0].'.'.$parts[1];
        return hash_equals(hash_hmac('sha256', $payload, $secret), $parts[2]) ? (int) $parts[0] : null;
    }
}
