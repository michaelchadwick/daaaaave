<?php

namespace Src\Controller;

use CustomResponse;
use Dotenv\Dotenv;

include PROJECT_ROOT_PATH . 'inc/config.php';

class DaveController extends BaseController {
  private $qsParams;

  public function __construct () {
    $dotenv = Dotenv::createImmutable(PROJECT_ROOT_PATH);
    $dotenv->load();

    $this->qsParams = $this->getQueryStringParams();
  }

  public function processRequest() {
    $allowedMethods = ['GET', 'OPTIONS'];
    $requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

    // all requests must be GET or OPTIONS
    if (!in_array($requestMethod, $allowedMethods)) {
      header('HTTP/1.1 405');
      echo json_encode(new CustomResponse(array(
        'message' => 'Dave says: Only got time for GETs and OPTIONSs, slick.',
        'status' => 405
      )));
      exit;
    }

    // no empty requests
    if ($requestMethod == 'GET') {
      if (!$this->qsParams) {
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'message' => 'Dave says: I think you forgot to ask for something. I know about ?binary, ?dave(s), ?http_code, ?json, ?slack, and ?text. See https://github.com/michaelchadwick/daaaaave for more, man.',
            'status' => '204'
          )))
        );

        exit;
      }

      // e.g. /?binary&size=1
      if (isset($this->qsParams['binary'])) {
        $this->_processBinary();
      }

      // e.g., /?daves=5
      // we are returning a json array of daves
      if (isset($this->qsParams['dave']) || isset($this->qsParams['daves'])) {
        $this->_processDave();
      }

      // e.g. /?http_code&type=0|2xx|3xx|4xx|5xx
      // if http code, return pre-scripted JSON object
      if (isset($this->qsParams['http_code'])) {
        $this->_processHttpCode($this->qsParams['http_code']);
      }

      // e.g. /?json&size=5
      if (isset($this->qsParams['json'])) {
        $this->_processJson();
      }

      // e.g. /?sites
      if (isset($this->qsParams['sites'])) {
        $this->_processSites();
      }

      // e.g. /?slack
      // if slack call, return data to slack
      if (isset($this->qsParams['slack'])) {
        $this->_processSlack();
      }

      // e.g. /?text&size=10
      if (isset($this->qsParams['text'])) {
        $this->_processText();
      }
    } elseif ($requestMethod == 'OPTIONS') {
      header('HTTP/1.1 200');
      echo json_encode(new CustomResponse(array(
        'message' => 'Dave says: Current OPTIONS available - ?daves, ?file, ?http_code, ?slack',
        'status' => 200
      )));
      exit;
    }
  }

  private function _processBinary() {
    $FILE_BIN_DEF_SIZE = 0;
    $FILE_BIN_MAX_SIZE = 50;

    header('Content-Description: File Transfer');
    header('Content-Transfer-Encoding: binary');

    $sizeInMB = (isset($_GET['size']) && $_GET['size'] >= 0) ? floor($_GET['size']) : $FILE_BIN_DEF_SIZE;

    if ($sizeInMB > $FILE_BIN_MAX_SIZE) $sizeInMB = $FILE_BIN_MAX_SIZE; // max request 100 MB for now

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

  private function _processDave() {
    $DAVE_DEF_SIZE = 1;
    $DAVE_MAX_SIZE = 1000;

    $daveArray = [];
    $daveCount = $DAVE_DEF_SIZE;
    $bypass = isset($_GET['bypass']);

    // grab potential filter and adjust amount of daves
    if (isset($this->qsParams['dave'])) {
      echo "dave? " . $this->qsParams['dave']; exit;
    }
    if (isset($this->qsParams['daves'])) {
      $daveCount = $this->qsParams['daves'];

      if ($daveCount <= 0) $daveCount = $DAVE_DEF_SIZE;

      if ($daveCount > $DAVE_MAX_SIZE && !$bypass) {
        $daveCount = $DAVE_MAX_SIZE;
      }
    }

    // build dave array
    for($i = 0; $i < $daveCount; $i++) {
      $daveArray[$i] = 'd' . (str_repeat('a', $i + 1)) . 've';
    }

    // return JSON of daves
    $this->sendOutput(
      json_encode(new CustomResponse(array(
        'body' => $daveArray,
        'error' => false
      )))
    );
  }

  private function _processHttpCode($code) {
    switch ($code) {
      case 0:
        header('HTTP/1.1 500');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'message' => 'Dave says: I am nothing.',
            'status' => 500,
          )))
        );
      case 200:
        header('HTTP/1.1 200');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'error' => false,
            'message' => 'Dave says: Woo!'
          )))
        );
      case 204:
        header('HTTP/1.1 204');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'error' => false,
            'message' => 'Dave says: ...',
            'status' => 204
          )))
        );
      case 301:
        header('HTTP/1.1 301');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'error' => false,
            'message' => 'Dave says: I moved, man.',
            'status' => 301
          )))
        );
      case 302:
        header('HTTP/1.1 302');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'error' => false,
            'message' => 'Dave says: I took a trip, man.',
            'status' => 302
          )))
        );
      case 400:
        header('HTTP/1.1 400');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'message' => 'Dave says: Bad to the bone, dude.',
            'status' => 400
          )))
        );
      case 401:
        header('HTTP/1.1 401');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'message' => 'Dave says: I can\'t do it, man. I lost my keys.',
            'status' => 400
          )))
        );
      case 403:
        header('HTTP/1.1 403');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'message' => 'Dave says: No way in.',
            'status' => 403
          )))
        );
      case 404:
        header('HTTP/1.1 404');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'message' => 'Dave says: I\'m not here, man.',
            'status' => 404
          )))
        );
      case 405:
        header('HTTP/1.1 405');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'message' => 'Dave says: I can\'t allow that here, guy.',
            'status' => 405
          )))
        );
      case 410:
        header('HTTP/1.1 410');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'message' => 'Dave says: I\' seriously not here, dude.',
            'status' => 410
          )))
        );
      case 418:
        header('HTTP/1.1 418');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'message' => 'Dave says: I only do coffee.',
            'status' => 418
          )))
        );
      case 444:
        header('HTTP/1.1 444');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'status' => 444
          )))
        );
      case 500:
        header('HTTP/1.1 500');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'message' => 'Dave says: I can\'t process that one, buddy.',
            'status' => 500
          )))
        );
      case 502:
        header('HTTP/1.1 502');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'message' => 'Dave says: I tried asking around, but got gibberish.',
            'status' => 502
          )))
        );
      default:
        header('HTTP/1.1 400 Bad Request');
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'message' => 'Dave says: I don\'t know, uh, know that code.',
            'status' => 400
          )))
        );
    }
  }

  private function _processJson() {
    $FILE_JSN_DEF_SIZE = 1;
    $FILE_JSN_MAX_SIZE = 100;
    $bypass = isset($_GET['bypass']);

    header('Content-Description: File Transfer');
    header('Content-Type: application/json');

    $sizeInItems = (isset($_GET['size']) && $_GET['size'] >= 0) ? $_GET['size'] : $FILE_JSN_DEF_SIZE;

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

  private function _processSites() {
    $sites = array(
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

    $this->sendOutput(
      json_encode(new CustomResponse(array(
        'body' => $sites,
        'error' => false
      )))
    );
  }

  private function _processSlack() {
    // did we get a Slack token?
    if (isset($this->qsParams['token'])) {
      $tokenExt = $this->qsParams['token'];

      $this->dotenv->required('DAVE_SLACK_TOKEN');
      $tokenInt = getenv('DAVE_SLACK_TOKEN');

      if (isset($tokenExt) && !empty($tokenInt)) {
        // token matches
        if ($tokenExt == $tokenInt) {
          $choice = rand(0, count($this->response) - 1);

          $flvSongs = array(
            'Docking',
            'Road Trip',
            'Charlotte Said',
            'Wondering Why',
            'The Ladder',
            'Fudge and Jam',
            'Tattoo Fetish',
            'Pinto',
            'Scenes From My Window',
            'Overjoyed',
            'Beyond Today',
            'Even Tide',
            'Presto Change-o',
            'SoCal Switchblade',
            'Unwound String',
            'Slow Boat',
            'Down and Out',
            'Humanity',
            'Down and Out',
            'Anodyne Blues',
            'Gains Gotten Ill',
            'Zepslider'
          );
          $flvReplies = array(
            '[song_macro]',
            'Uh, that\'s *David* to you. Heh. Just kidding! Dave is fine.',
            'Dave\'s not here right now.',
            'You called?',
            'Yeah, Dave is with you, man.',
            'Damn right Dave is down.',
            '*You got it!*',
            '*Hell yes!*',
            '*Woo!*',
            'Dave is caught in an interdimensional rock warp, but will be back soon.',
            'Alive and all that jive.',
            'Just reeling from how badass ya\'ll are.',
            'Dave has got your back.',
            'I\'m on my way.',
            'Keep *rockin\'*, and Dave will be there.',
            'Just call me _Daaaaaaave_.',
            'I\'m feeling just the right amount of *Dave* today, buddy.',
            'You rang?',
            'I\'m still here.',
            'What\'s shakin\', bacon?',
            'Go for Dave.'
          );

          if ($choice == 0) {
            $this->sendOutput(
              json_encode(array(
                'response_type' => 'in_channel',
                'text' => 'Hey, just listening to _' . $flvSongs[rand(0, count($flvSongs) - 1)] . '_ right now. It *rules*!'
              ))
            );
          } else {
            $this->sendOutput(
              json_encode(array(
                'response_type' => 'in_channel',
                'text' => $flvReplies[rand(0, count($flvReplies) - 1)]
              ))
            );
          }
        } else { // invalid token
          $this->sendOutput(
            json_encode(array(
              'text' => 'Dave says: \'What? I didn\'t understand that, dude.\''
            ))
          );
        }
      }
      // missing internal token
      else {
        $this->sendOutput(
          json_encode(array(
            'text' => 'Dave says: \'Eh? I don\'t think I know you, buddy.\''
          ))
        );
      }
    } else { // missing external token
      $this->sendOutput(
        json_encode(array(
          'text' => 'Dave says: \'Eh? I don\'t think I know you, buddy.\''
        ))
      );
    }
  }

  private function _processText() {
    $FILE_TXT_DEF_SIZE = 5;
    $FILE_TXT_MAX_SIZE = 50;

    header('Content-Description: File Transfer');
    header('Content-Type: text/plain');

    $sizeInLines = (isset($_GET['size']) && $_GET['size'] >= 0) ? $_GET['size'] : $FILE_TXT_DEF_SIZE;

    if ($sizeInLines > $FILE_TXT_MAX_SIZE) $sizeInLines = $FILE_TXT_MAX_SIZE; // max request 100 lines for now

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