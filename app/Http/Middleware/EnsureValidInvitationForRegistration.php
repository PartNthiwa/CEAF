<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Invitation;

class EnsureValidInvitationForRegistration
{
    public function handle(Request $request, Closure $next)
    {

        if (! $request->routeIs('register', 'register.store')) {
            return $next($request);
        }

        $token = $request->input('token') ?? $request->query('token');

        if (! $token) {
            // abort(403, 'Registration requires an invitation link.');
            return redirect()->route('invite-required');
        }

        $inv = Invitation::where('token', $token)->first();

        if (! $inv) abort(403, 'Invalid invitation token.');
        if ($inv->used_at) abort(403, 'This invitation has already been used.');
        if ($inv->expires_at && now()->gt($inv->expires_at)) abort(403, 'This invitation has expired.');

        return $next($request);
    }
}
