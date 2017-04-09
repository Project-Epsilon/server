<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyOTP
{
    /**
     * Returns the appropriate response
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->notUnlocked($request)){
            return new JsonResponse([
                'errors' => ['message' => 'Account locked.']
            ], 422);
        }

        return $next($request);
    }

    /**
     * Verifies if user is not unlocked.
     *
     * @param Request $request
     * @return bool
     */
    protected function notUnlocked(Request $request)
    {
        return $request->user()->otp != null;
    }
}
