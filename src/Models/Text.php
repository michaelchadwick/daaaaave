<?php

namespace Dave\Models;

class Text
{
  public function __construct($size)
  {
    $FILE_TXT_DEF_SIZE = 5;
    $FILE_TXT_MAX_SIZE = 50;

    header('Content-Description: File Transfer');
    header('Content-Type: text/plain');

    $sizeInLines = (isset($size) && $size >= 0) ? $size : $FILE_TXT_DEF_SIZE;

    // max request 100 lines for now
    if ($sizeInLines > $FILE_TXT_MAX_SIZE) {
      $sizeInLines = $FILE_TXT_MAX_SIZE;
    }

    $filePath = '/tmp/' . $sizeInLines . '.txt';
    $cmd = './assets/scripts/rand_name.rb ' . $sizeInLines . ' > ' . $filePath;

    shell_exec($cmd);

    header('Content-Length: ' . filesize($filePath));
    flush(); // Flush system output buffer
    echo file_get_contents($filePath);
    unlink($filePath);
    exit();
  }
}
