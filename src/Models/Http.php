<?php

namespace Dave\Models;

class Http
{
  var $error = false;
  var $message;
  var $status;

  public function __construct($code)
  {
    switch ($code) {
      case 0:
        $this->message = 'Dave says: I am nothing.';
        $this->status = 500;
        break;
      case 200:
        $this->message = 'Dave says: Woo!';
        $this->status = 200;
        break;
      case 204:
        $this->message = 'Dave says: ...';
        $this->status = 204;
        break;
      case 301:
        $this->message = 'Dave says: I moved, man.';
        $this->status = 301;
        break;
      case 302:
        $thismessage = 'Dave says: I took a trip, man.';
        $this->status = 302;
        break;
      case 400:
        $this->message = 'Dave says: Bad to the bone, dude.';
        $this->status = 400;
        break;
      case 401:
        $this->message = 'Dave says: I can\'t do it, man. I lost my keys.';
        $this->status = 401;
        break;
      case 403:
        $this->message = 'Dave says: No way in.';
        $this->status = 403;
        break;
      case 404:
        $this->message = 'Dave says: I\'m not here, man.';
        $this->status = 404;
        break;
      case 405:
        $this->message = 'Dave says: I can\'t allow that here, guy.';
        $this->status = 405;
        break;
      case 410:
        $this->message = 'Dave says: I\'m seriously not here, dude.';
        $this->status = 410;
        break;
      case 418:
        $this->message = 'Dave says: I only do coffee.';
        $this->status = 418;
        break;
      case 444:
        $this->message = '';
        $this->status = 444;
        break;
      case 500:
        $this->message = 'Dave says: I can\'t process that one, buddy.';
        $this->status = 500;
        break;
      case 502:
        $this->message = 'Dave says: I tried asking around, but got gibberish.';
        $this->status = 500;
        break;
      default:
        $this->error = true;
        $this->message = 'Dave says: I don\'t know, uh, know that code.';
        $this->status = 400;
        break;
    }
  }
}
