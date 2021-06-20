<?php

namespace App\Http\Controllers\Apis\v1\UsersWS;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Traits\HandlesJsonResponse;

class ResetPasswordController extends Controller
{
  use HandlesJsonResponse;

  public function resetPassword(Request $request){
    $res = Http::withHeaders([
    'Content-Type' => 'application/json',
    ])->post(config('usersws.url').'/api/v1/user/password/reset', $request->all());

    $response = json_decode($res, true);

    return $response = is_array($response) ? response()->json($response, $res->status()) : $res;
  }
}
