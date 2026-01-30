<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMemberApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $member = $user?->member;

        // No member record or not approved yet
        if (!$member || is_null($member->approved_at)) {
            return redirect()->route('pending');
        }

        return $next($request);
    }


}
