<?php

namespace App\Http\Middleware;

use Closure;

class ApplePurchaseHeaderValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('username') === config('mocking-APIs.APPLE_PURCHASE_USERNAME')
            && $request->header('password') === config('mocking-APIs.APPLE_PURCHASE_PASSWORD') ) {
            return $next($request);
        }

        return failureResponse(422, __('Username and password are invalid'));
    }
}
