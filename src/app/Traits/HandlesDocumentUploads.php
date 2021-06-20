<?php

namespace App\Traits;

use Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

trait HandlesDocumentUploads
{
  private $appLocalEnv = 'local';

  public function uploadDocument(Request $request){
    $file = $request->document;
    $originalFileName = $file->getClientOriginalName();
    $fileName = time()."-" .$originalFileName;

    $env = config('app.env');

    if($env === $this->appLocalEnv){
      $hasUploaded = $file->storeAs($request->path, $fileName) ? true : false;
    }else{
      $hasUploaded = $file->storeAs($request->path, $fileName, $request->disk) ? true : false;
    }

    if(!$hasUploaded){
      return false;
    }

    return $fileName;
  }
}
