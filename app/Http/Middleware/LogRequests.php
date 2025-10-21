<?php

namespace App\Http\Middleware;

use App\Models\UserRequest;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class LogRequests
{
    public function handle($request, Closure $next)
    {
        UserRequest::create([
            'user_id' =>  Auth::id(), 
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'payload' => json_encode($request->all(),JSON_UNESCAPED_UNICODE),
        ]);

        return $next($request);
    }
}
