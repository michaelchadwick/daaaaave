<?php

namespace Dave\Models;

class Json {
  public function __construct($size) {
    // TODO: can't get more than 599 JSON items ([related bug](https://github.com/jpmens/jo/issues/132))
    $FILE_JSN_DEF_SIZE = 1;
    $FILE_JSN_MAX_SIZE = 100;
    $bypass = isset($_GET['bypass']);

    header('Content-Description: File Transfer');
    header('Content-Type: application/json');

    $sizeInItems = (isset($size) && $size >= 0) ? $size : $FILE_JSN_DEF_SIZE;

    if ($sizeInItems > $FILE_JSN_MAX_SIZE && !$bypass) {
      $sizeInItems = $FILE_JSN_MAX_SIZE;
    }

    $filePath = '/tmp/' . $sizeInItems . '.json';

    $cmd = './assets/scripts/rand_json.sh ' . $sizeInItems . ' > ' . $filePath . ' 2>&1';

    shell_exec($cmd);

    header('Content-Length: ' . filesize($filePath));
    flush(); // Flush system output buffer
    echo file_get_contents($filePath);
    unlink($filePath);
    exit();
  }
}
