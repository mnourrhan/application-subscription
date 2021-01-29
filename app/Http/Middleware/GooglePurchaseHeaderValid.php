<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class GooglePurchaseHeaderValid
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
        if ($request->header('username') === config('mocking-APIs.GOOGLE_PURCHASE_USERNAME')
            && $request->header('password') === config('mocking-APIs.GOOGLE_PURCHASE_PASSWORD') ) {
            return $next($request);
        }

        return failureResponse(422, __('Username and password are invalid'));
    }
}
