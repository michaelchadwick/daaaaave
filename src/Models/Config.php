<?php

namespace Dave\Models;

class Config
{
  var $value;

  public function __construct()
  {
    $apiMajor = rand(0, 5);
    $apiMinor = rand(0, 20);
    $appMajor = rand(0, 5);
    $appMinor = rand(0, 200);
    $appPatch = rand(0, 20);
    $maxUploadSize = rand(1024, 1000000000);
    $searchEnabled = array_rand(array(True, False));
    $trackingEnabled = array_rand(array(True, False));

    $this->value = array(
      "config" => array(
        "type" => "form",
        "locale" => "en",
        "apiVersion" => "v$apiMajor.$apiMinor",
        "appVersion" => "$appMajor.$appMinor.$appPatch",
        "maxUploadSize" => $maxUploadSize,
        "searchEnabled" => boolval($searchEnabled),
        "trackingEnabled" => boolval($trackingEnabled),
        "userSearchType" => "local"
      )
    );
  }
}
