<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        foreach($roles as $role) {
            if($user->role === $role) {
                return $next($request);
            }
        }

        return redirect('/')->with('error', 'Unauthorized access');
    }
}
