<?php

return [
  'url' => env('GATEWAYWS_USERSWS_URL', 'host.docker.internal:8000'),
  'client' => [
    'public' => env('GATEWAYWS_USERSWS_CLIENT_PUBLIC'),
    'secret' => env('GATEWAYWS_USERSWS_CLIENT_SECRET'),
  ]
];
