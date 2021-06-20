<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function() {
    if(config('app.env') != "production"){
      return response()->json([
        'status' => true,
        'data' => [
          'key' => Illuminate\Support\Str::random(32),
        ],
        'message' => 'Welcome to CashEnvoy!'
      ], 200);
    }

    return response()->json([
      'status' => true,
      'message' => 'Welcome to CashEnvoy!'
    ], 200);
});

$router->get('/health', function() {
    return response()->json([
      'status' => true,
    ], 200);
});


$router->group([
  'prefix' => 'api',
], function() use ($router) {
  /* Version 1 */
  $router->group([
    'prefix' => 'v1',
  ], function() use ($router) {
    $router->group([
      'prefix' => 'gateway'
    ], function() use ($router) {
      $router->post('/register', 'Apis\v1\UsersWS\RegisterController@register');
      $router->post('/login', 'Apis\v1\UsersWS\LoginController@login');

      $router->post('/token/refresh', 'Apis\v1\UsersWS\LoginController@refreshToken');

      $router->group([
        'prefix' => 'password'
      ], function() use ($router) {
        $router->post('/email', 'Apis\v1\UsersWS\ForgotPasswordController@sendPasswordResetEmail');
        $router->post('/reset', 'Apis\v1\UsersWS\ResetPasswordController@resetPassword');
      });

      $router->post('/business/name/verify', 'Apis\v1\BusinessWS\RegisterBusinessController@checkName');
      /*
      |--------------------------------------------------------------------------
      | Oauth Middleware Protected Routes
      |--------------------------------------------------------------------------
      |
      | Only authenticated users can access the routes below.
      */
      $router->group([
        'middleware' => 'oauth'
      ], function() use ($router) {
        $router->post('/logout', 'Apis\v1\UsersWS\LoginController@logout');

        $router->group([
          'prefix' => 'email',
        ], function() use ($router) {
          $router->post('/verify', 'Apis\v1\UsersWS\VerifyEmailController@verifyEmail');
          $router->post('/resend', 'Apis\v1\UsersWS\VerifyEmailController@resendVerificationCode');
          $router->put('/update', 'Apis\v1\UsersWS\VerifyEmailController@updateEmail');
        });

        /*
        |--------------------------------------------------------------------------
        | Authorize Middleware Protected Routes
        |--------------------------------------------------------------------------
        |
        | Only users with the required permissions can access the routes below.
        */
        $router->group([
          'prefix' => 'type'
        ], function() use ($router) {
          $router->post('/create', 'Apis\v1\BusinessWS\TypeController@create');
          $router->get('/get[/{limit}]', 'Apis\v1\BusinessWS\TypeController@fetch');
          $router->put('/update', 'Apis\v1\BusinessWS\TypeController@update');
          $router->delete('/delete', 'Apis\v1\BusinessWS\TypeController@destroy');
        });

        $router->group([
          'prefix' => 'business'
        ], function() use ($router) {
          $router->post('/register', 'Apis\v1\BusinessWS\RegisterBusinessController@register');
          $router->get('/get[/{limit}]', 'Apis\v1\BusinessWS\UserBusinessController@fetchBusinesses');
          $router->post('/activate', 'Apis\v1\BusinessWS\UserBusinessController@switchActiveBusiness');
          $router->post('/toggle/mode', 'Apis\v1\BusinessWS\UserBusinessController@toggleBusinessMode');
          $router->put('/update', 'Apis\v1\BusinessWS\UserBusinessController@updateBusiness');

          //business documents
          $router->group([
            'prefix' => 'document'
          ], function() use ($router) {
            $router->post('/upload', 'Apis\v1\BusinessWS\BusinessDocumentController@uploadBusinessDocument');
            $router->get('/compliance', 'Apis\v1\BusinessWS\BusinessDocumentController@fetchComplianceDocuments');
            $router->get('/get[/{limit}]', 'Apis\v1\BusinessWS\BusinessDocumentController@fetchBusinessDocument');
          });
        });

        $router->group([
          'prefix' => 'company'
        ], function() use ($router) {
          $router->post('/create', 'Apis\v1\BusinessWS\CompanyController@create');
          $router->get('/get[/{limit}]', 'Apis\v1\BusinessWS\CompanyController@fetch');
          $router->put('/update', 'Apis\v1\BusinessWS\CompanyController@update');
          $router->delete('/delete', 'Apis\v1\BusinessWS\CompanyController@destroy');
        });

        $router->group([
          'prefix' => 'sector'
        ], function() use ($router) {
          $router->post('/create', 'Apis\v1\BusinessWS\SectorController@create');
          $router->get('/get[/{limit}]', 'Apis\v1\BusinessWS\SectorController@fetch');
          $router->put('/update', 'Apis\v1\BusinessWS\SectorController@update');
          $router->delete('/delete', 'Apis\v1\BusinessWS\SectorController@destroy');
        });

        $router->group([
          'prefix' => 'industry'
        ], function() use ($router) {
          $router->post('/create', 'Apis\v1\BusinessWS\IndustryController@create');
          $router->get('/get[/{limit}]', 'Apis\v1\BusinessWS\IndustryController@fetch');
          $router->put('/update', 'Apis\v1\BusinessWS\IndustryController@update');
          $router->delete('/delete', 'Apis\v1\BusinessWS\IndustryController@destroy');
        });

        $router->group([
          'prefix' => 'document'
        ], function() use ($router) {
          $router->post('/create', 'Apis\v1\BusinessWS\DocumentController@create');
          $router->get('/get[/{limit}]', 'Apis\v1\BusinessWS\DocumentController@fetch');
          $router->put('/update', 'Apis\v1\BusinessWS\DocumentController@update');
          $router->delete('/delete', 'Apis\v1\BusinessWS\DocumentController@destroy');
        });

        $router->group([
          'prefix' => 'account'
        ], function() use ($router) {
          $router->post('/verify', 'Apis\v1\AccountWS\BankAccountController@verifyBankAccount');
          $router->post('/create', 'Apis\v1\AccountWS\BankAccountController@createAccount');
        });

        $router->group([
          'prefix' => 'admin'
        ], function() use ($router) {
          $router->group([
            'prefix' => 'business'
          ], function() use ($router) {
            $router->post('/toggle/verify', 'Apis\v1\BusinessWS\UserBusinessController@toggleBusinessVerificationState');
          });
        });
      });
    });
  });
  /* Version 1 */
});
