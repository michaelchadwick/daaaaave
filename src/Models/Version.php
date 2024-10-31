<?php

namespace Dave\Models;

class Version
{
  var $value;
  var $major;
  var $minor;
  var $patch;

  public function __construct()
  {
    $major = rand(0, 20);
    $minor = rand(0, 200);
    $patch = rand(0, 9999);

    $this->value = "$major.$minor.$patch";
  }
}
