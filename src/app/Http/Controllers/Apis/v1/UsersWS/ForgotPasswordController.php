<?php

namespace App\Http\Controllers\Apis\v1\UsersWS;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Traits\HandlesJsonResponse;

class ForgotPasswordController extends Controller
{
  use HandlesJsonResponse;

  public function sendPasswordResetEmail(Request $request){
    $rules = [
        'email' => 'required|string|email|max:255'
    ];

    $validator = Validator::make($request->all(), $rules);

    if($validator->fails()){
      return $this->jsonValidationError($validator);
    }

    $res = Http::withHeaders([
    'Content-Type' => 'application/json',
    ])->post(config('usersws.url').'/api/v1/user/password/email', $request->all());

    $response = json_decode($res, true);

    //send password reset email
    // Http::withHeaders([
    //   'Content-Type' => 'application/json',
    // ])->post(config('notificationsws.url').'/api/v1/notification/send', [
    //   'channels' => ['sms', 'email'],
    //   'recipients' => [$request->all()],
    //   'templateName' => 'Password_Reset',
    //   'templateData' => [
    //     'token_link' => config('notificationsws.token_url_base').'/reset-password/'.$response['token']
    //   ],
    // ]);

    return $response = is_array($response) ? response()->json($response, $res->status()) : $res;
  }
}
