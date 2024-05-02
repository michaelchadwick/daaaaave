<?php

namespace Dave\Models;

class Sites {
  public function __construct() {
    print_r(array(
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
    ));
  }
}
