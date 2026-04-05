<?php

namespace XLock\Laravel;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class XLockMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $siteKey = config('xlock.site_key', env('XLOCK_SITE_KEY'));
        $apiUrl = config('xlock.api_url', env('XLOCK_API_URL', 'https://api.x-lock.dev'));
        $failOpen = config('xlock.fail_open', true);

        if (!$siteKey || $request->method() !== 'POST') {
            return $next($request);
        }

        $token = $request->header('x-lock');

        if (!$token) {
            return response()->json([
                'error' => 'Blocked by x-lock: missing token',
            ], 403);
        }

        try {
            if (str_starts_with($token, 'v3.')) {
                $parts = explode('.', $token, 3);
                $sessionId = $parts[1];
                $enforceUrl = "{$apiUrl}/v3/session/enforce";
                $enforceBody = [
                    'sessionId' => $sessionId,
                    'siteKey' => $siteKey,
                    'path' => $request->path(),
                ];
            } else {
                $enforceUrl = "{$apiUrl}/v1/enforce";
                $enforceBody = [
                    'token' => $token,
                    'siteKey' => $siteKey,
                    'path' => $request->path(),
                ];
            }

            $response = Http::timeout(5)->post($enforceUrl, $enforceBody);

            if ($response->status() === 403) {
                return response()->json([
                    'error' => 'Blocked by x-lock',
                    'reason' => $response->json('reason'),
                ], 403);
            }

            if ($response->failed() && !$failOpen) {
                return response()->json([
                    'error' => 'x-lock verification failed',
                ], 403);
            }
        } catch (\Exception $e) {
            Log::error('[x-lock] Enforcement error: ' . $e->getMessage());

            if (!$failOpen) {
                return response()->json([
                    'error' => 'x-lock verification failed',
                ], 403);
            }
        }

        return $next($request);
    }
}
