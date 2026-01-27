<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $status = auth()->check() ? auth()->user()->member->membership_status : null;

        if ($status && in_array($status, ['late', 'suspended', 'terminated'])) {
            abort(403, 'Access denied. Active membership required.');
        }
        return $next($request);
    }


}
