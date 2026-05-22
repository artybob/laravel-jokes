<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BasicAuth
{
    public function handle(Request $request, Closure $next)
    {
        $username = $request->getUser();
        $password = $request->getPassword();

        if ($username !== 'admin' || $password !== 'secret123') {
            return response()->json(['error' => 'Unauthorized'], 401, [
                'WWW-Authenticate' => 'Basic realm="Statistics"'
            ]);
        }

        return $next($request);
    }
}
