<?php

namespace Dave\Models;

class Binary {
  public function __construct($size) {
    $FILE_BIN_DEF_SIZE = 0;
    $FILE_BIN_MAX_SIZE = 10;

    header('Content-Description: File Transfer');
    header('Content-Transfer-Encoding: binary');

    $sizeInMB = (isset($size) && $size >= 0) ? floor($size) : $FILE_BIN_DEF_SIZE;

    // max request 10 MB for now
    if ($sizeInMB > $FILE_BIN_MAX_SIZE) {
      $sizeInMB = $FILE_BIN_MAX_SIZE; 
    }

    $sizeInBytes = $sizeInMB * 1024 * 1024;
    $filePath = '/tmp/' . $sizeInMB . 'mb_of_dave';

    if (PHP_OS_FAMILY == 'Darwin') {
      shell_exec('head -c ' . $sizeInBytes . ' /dev/zero > ' . $filePath);
    } else {
      // creates a binary file of a specific size
      shell_exec('fallocate -l ' . $sizeInBytes . ' ' . $filePath);
    }

    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    header('Content-Length: ' . filesize($filePath));
    flush(); // Flush system output buffer
    readfile($filePath);
    unlink($filePath);
    exit();
  }
}
