<?php

namespace App\Http\Controllers\Apis\v1\UsersWS;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Traits\HandlesJsonResponse;

class LoginController extends Controller
{
  use HandlesJsonResponse;

  private $contentTypeHeader = 'Content-Type';
  private $applicationJson = 'application/json';
  private $userServiceUrl = 'usersws.url';
  private $userEndpoint = '/api/v1/user';
  private $clientPublic = 'Client-Public';
  private $clientSecret = 'Client-Secret';
  private $refreshToken = 'Refresh-Token';
  private $authorization = 'Authorization';

  public function login(Request $request){
    $res = Http::withHeaders([
      $this->contentTypeHeader => $this->applicationJson,
      $this->clientPublic => $request->header($this->clientPublic),
      $this->clientSecret => $request->header($this->clientSecret)
    ])->post(config($this->userServiceUrl).$this->userEndpoint.'/authenticate', $request->all());

    $response = json_decode($res, true);

    return $response = is_array($response) ? response()->json($response, $res->status()) : $res;
  }

  public function logout(Request $request){
    $res = Http::withHeaders([
      $this->contentTypeHeader => $this->applicationJson,
      $this->authorization => $request->header($this->authorization),
    ])->post(config($this->userServiceUrl).$this->userEndpoint.'/token/revoke', $request->all());

    $response = json_decode($res, true);

    return $response = is_array($response) ? response()->json($response, $res->status()) : $res;
  }

  public function refreshToken(Request $request){
    $res = Http::withHeaders([
      $this->contentTypeHeader => $this->applicationJson,
      $this->clientPublic => $request->header($this->clientPublic),
      $this->clientSecret => $request->header($this->clientSecret),
      $this->refreshToken => $request->header($this->refreshToken)
    ])->post(config($this->userServiceUrl).$this->userEndpoint.'/token/refresh', $request->all());

    $response = json_decode($res, true);

    return $response = is_array($response) ? response()->json($response, $res->status()) : $res;
  }
}
