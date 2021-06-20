<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Http;

class CheckPermissions
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
        $path = $request->getPathInfo();

        $method = $request->method();

        $accept = $request->header('accept');

        $res = Http::withHeaders([
          'Content-Type' => 'application/json',
          'Accept' => $accept,
          'Path' => $path,
          'Method' => $method
        ])->post(config('authorizationws.url').'/api/v1/authorization/authorize', $request->all());

        $response = json_decode($res, true);

        if($res->status() !== 200) return response()->json($response, $res->status());

        return $next($request);
    }
}
