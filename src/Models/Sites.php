<?php

namespace Dave\Models;

class Sites
{
  var $list;

  public function __construct()
  {
    $this->list = array(
      [
        'title' => 'Deckdle',
        'url' => 'https://deckdle.neb.host'
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
        'title' => 'Audio Hash',
        'url' => 'https://neb.host/apps/audiohash'
      ],
      [
        'title' => 'Bogdle',
        'url' => 'https://bogdle.neb.host'
      ],
      [
        'title' => 'Gem Warrior',
        'url' => 'https://neb.host/apps/gemwarrior'
      ],
      [
        'title' => 'Sketchage',
        'url' => 'https://neb.host/apps/sketchage'
      ],
      [
        'title' => 'SoundLister',
        'url' => 'https://soundlister.neb.host'
      ]
    );

    return $this->list;
  }
}
