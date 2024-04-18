<?php

namespace Dave\Models;

class Sites {
  var $list;

  public function __construct() {
    $this->list = array(
      [
        'title' => 'Audio Hash',
        'url' => 'https://ah.neb.host'
      ],
      [
        'title' => 'Bogdle',
        'url' => 'https://bogdle.neb.host'
      ],
      [
        'title' => 'Gem Warrior',
        'url' => 'https://gw.neb.host'
      ],
      [
        'title' => 'Keebord',
        'url' => 'https://keebord.neb.host'
      ],
      [
        'title' => 'Raffler',
        'url' => 'https://raffler.neb.host'
      ],
      [
        'title' => 'Sketchage',
        'url' => 'https://sketchage.neb.host'
      ],
      [
        'title' => 'SoundLister',
        'url' => 'https://soundlister.neb.host'
      ]
    );
  }
}
