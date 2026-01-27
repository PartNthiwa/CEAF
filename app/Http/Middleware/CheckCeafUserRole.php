<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ceaf;

class CheckCeafUserRole
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        $ceafUser = Ceaf::where('email', $user->email)->first();

        if (!$ceafUser || $ceafUser->role !== 'admin') {
                abort(403); 
        }
        return $next($request);
    }
}
