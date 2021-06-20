<?php



return [
  'disk' => env('GATEWAYWS_STORAGE_DISK', 's3'),
  'folder' => env('GATEWAYWS_STORAGE_FOLDER', 'documents'),
];
