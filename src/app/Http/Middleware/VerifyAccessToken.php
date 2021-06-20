<?php

namespace App\Http\Middleware;

use Closure;
use App\Trait\HandlesJsonResponse;
use Illuminate\Support\Facades\Http;

class VerifyAccessToken
{
    use HandlesJsonResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $res = Http::withHeaders([
          'Content-Type' => 'application/json',
          'Authorization' => $request->header('Authorization'),
        ])->get(config('usersws.url').'/api/v1/user/token/validate', $request->all());

        $response = json_decode($res, true);

        if($response){
          $request->merge($response);
        }

        if($res->status() !== 200) return $this->jsonResponse(__('response.messages.token_invalid'), __('response.codes.unauthenticated'), $res->status(), [], __('response.errors.unauthenticated'));

        return $next($request);
    }
}
