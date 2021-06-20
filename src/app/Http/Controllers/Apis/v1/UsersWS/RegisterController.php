<?php

namespace App\Http\Controllers\Apis\v1\UsersWS;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Apis\v1\UsersWS\LoginController;
use App\Traits\HandlesJsonResponse;

class RegisterController extends LoginController
{
  use HandlesJsonResponse;

  private $contentTypeHeader = 'Content-Type';
  private $applicationJson = 'application/json';
  private $clientPublic = 'Client-Public';
  private $clientSecret = 'Client-Secret';

  public function register(Request $request){
    $res = Http::withHeaders([
      $this->contentTypeHeader => $this->applicationJson,
      $this->clientPublic => $request->header($this->clientPublic),
      $this->clientSecret => $request->header($this->clientSecret)
    ])->post(config('usersws.url').'/api/v1/user', $request->all());

    $response = json_decode($res, true);

    if($res->status() == 201 && $response['status']) {
      return $this->login($request);
    }

    return $response = is_array($response) ? response()->json($response, $res->status()) : $response->json();
  }
}
