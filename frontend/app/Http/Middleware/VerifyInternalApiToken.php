<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Support\AgentIdentity;
use App\Models\User;

class VerifyInternalApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Internal-Token');
        $expectedToken = config('services.internal_api.token');

        $userId = AgentIdentity::verify($request->header('X-Agent-Identity'));
        if (!is_string($expectedToken) || $expectedToken === '' || !is_string($token)
            || !hash_equals($expectedToken, $token) || !$userId || !User::whereKey($userId)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->attributes->set('agent_user_id', $userId);
        return $next($request);
    }
}