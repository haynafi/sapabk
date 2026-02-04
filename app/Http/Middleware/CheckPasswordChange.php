<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordChange
{
    protected array $except = [
        'password.change',
        'password.update',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            // Allow access to password change routes and logout
            if (!in_array($request->route()->getName(), $this->except)) {
                return redirect()->route('password.change');
            }
        }

        return $next($request);
    }
}
