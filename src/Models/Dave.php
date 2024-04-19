<?php

namespace Dave\Models;

class Dave {
  var $daves;

  public function __construct($dave, $type, $bypass) {
    $DAVE_MIN_SIZE = 1;
    $DAVE_MAX_SIZE = 1000;

    $daveArray = [];
    $daveCount = $DAVE_MIN_SIZE;

    if ($type == 'single') {
      array_push($daveArray, 'dave!');
    } else {
      // check for non-numeric
      if (isset($dave) && is_numeric($dave)) {
        $daveCount = $dave;
      }

      // check for proper range value
      if ($daveCount <= 0) {
        $daveCount = $DAVE_MIN_SIZE;
      }
      if ($daveCount > $DAVE_MAX_SIZE && !$bypass) {
        $daveCount = $DAVE_MAX_SIZE;
      }

      // build dave array
      for($i = 0; $i < $daveCount; $i++) {
        $daveArray[$i] = 'd' . (str_repeat('a', $i + 1)) . 've';
      }
    }

    $this->daves = $daveArray;
  }
}
