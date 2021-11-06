<?php

namespace App\Http\Middleware;

use Closure;

class ValidateCowpaySignature
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
        $signature = md5(
            env('COWPAY_MERCHANTHASHKEY').
            $request->amount.
            $request->cowpay_reference_id.
            $request->merchant_reference_id.
            $request->order_status
        );
        return $signature == $request->signature ? $next($request) : response()->json(['Unauthenticated'], 401);
    }
}
