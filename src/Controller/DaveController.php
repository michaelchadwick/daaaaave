<?php

namespace Src\Controller;

use CustomResponse;
use Dotenv\Dotenv;

include PROJECT_ROOT_PATH . 'inc/config.php';

class DaveController extends BaseController {
  private $songs = array(
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
  private $responses = array(
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
            'message' => 'Dave says: I think you forgot to ask for something.',
            'status' => '204'
          )))
        );

        exit;
      }

      // e.g. /?slack
      // if slack call, return data to slack
      if (isset($this->qsParams['slack'])) {
        $this->_processSlack();
      }

      // e.g. /?http_code&type=0|2xx|3xx|4xx|5xx
      // if http code, return pre-scripted JSON object
      if (isset($this->qsParams['http_code'])) {
        $this->_processHttpCode($this->qsParams['http_code']);
      }

      // e.g. /?file&type=data|json|text&size=1|10|100|1000
      // if file, check type and size, return file
      if (isset($this->qsParams['file'])) {
        $this->_processFile();
      }

      // e.g., /?daves=5
      // we are returning a json array of daves
      if (isset($this->qsParams['dave']) || isset($this->qsParams['daves'])) {
        $this->_processDave();
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

  private function _processDave() {
    $DAVE_DEF_SIZE = 1;
    $DAVE_MAX_SIZE = 1000;

    $daveArray = [];
    $daveCount = $DAVE_DEF_SIZE;

    // grab potential filter and adjust amount of daves
    if (isset($this->qsParams['dave'])) {
      echo "dave? " . $this->qsParams['dave']; exit;
    }
    if (isset($this->qsParams['daves'])) {
      $daveCount = $this->qsParams['daves'];

      if ($daveCount <= 0) $daveCount = $DAVE_DEF_SIZE;
      if ($daveCount > $DAVE_MAX_SIZE) $daveCount = $DAVE_MAX_SIZE;
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

  private function _processFile() {
    $FILE_BIN_DEF_SIZE = 0;
    $FILE_JSN_DEF_SIZE = 1;
    $FILE_TXT_DEF_SIZE = 5;

    $FILE_BIN_MAX_SIZE = 50;
    $FILE_JSN_MAX_SIZE = 100;
    $FILE_TXT_MAX_SIZE = 50;

    if (isset($this->qsParams['type'])) {
      $fileType = $this->qsParams['type'];

      switch ($fileType) {
        case 'binary':
          $sizeInMB = (isset($_GET['size']) && $_GET['size'] >= 0) ? floor($_GET['size']) : $FILE_BIN_DEF_SIZE;

          if ($sizeInMB > $FILE_BIN_MAX_SIZE) $sizeInMB = $FILE_BIN_MAX_SIZE; // max request 100 MB for now

          $sizeInBytes = $sizeInMB * 1024 * 1024;
          $filePath = './tmp/' . $sizeInMB . 'mb_of_dave';

          if (PHP_OS_FAMILY == 'Darwin') {
            shell_exec('head -c ' . $sizeInBytes . ' /dev/zero > ' . $filePath);
          } else {
            // creates a binary file of a specific size
            shell_exec('fallocate -l ' . $sizeInBytes . ' ' . $filePath);
          }

          header('Content-Description: File Transfer');
          header('Content-Transfer-Encoding: binary');
          header('Content-Type: application/force-download');
          header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
          header('Content-Length: ' . filesize($filePath));
          flush(); // Flush system output buffer
          readfile($filePath);
          unlink($filePath);
          exit();

        case 'json':
          $sizeInItems = (isset($_GET['size']) && $_GET['size'] >= 0) ? $_GET['size'] : $FILE_JSN_DEF_SIZE;

          if ($sizeInItems > $FILE_JSN_MAX_SIZE) $sizeInItems = $FILE_JSN_MAX_SIZE;

          switch ($sizeInItems) {
            case '1':
              $filePath = './assets/json/1.json';
              break;
            case '10':
              $filePath = './assets/json/10.json';
              break;
            case '100':
              $filePath = './assets/json/100.json';
              break;
            case '1000':
              $filePath = './assets/json/1000.json';
              break;
            default:
              header('HTTP/1.1 400 Bad Request');
              $this->sendOutput(
                json_encode(new CustomResponse(array(
                  'message' => 'Dave says: That size ain\'t here, man.',
                  'status' => 400
                )))
              );
          }

          echo file_get_contents($filePath);
          exit();

        case 'text':
          header('Content-Description: File Transfer');
          header('Content-Type: text/plain');

          $sizeInLines = (isset($_GET['size']) && $_GET['size'] >= 0) ? $_GET['size'] : $FILE_TXT_DEF_SIZE;

          if ($sizeInLines > $FILE_TXT_MAX_SIZE) $sizeInLines = $FILE_TXT_MAX_SIZE; // max request 100 lines for now

          $filePath = './tmp/' . $sizeInLines . '.txt';

          shell_exec('ruby ./assets/scripts/rand_name.rb ' . $sizeInLines . ' > ' . $filePath);

          header('Content-Length: ' . filesize($filePath));
          flush(); // Flush system output buffer
          echo file_get_contents($filePath);
          unlink($filePath);
          exit();

        default:
          header('HTTP/1.1 400 Bad Request');
          $this->sendOutput(
            json_encode(new CustomResponse(array(
              'message' => 'You did not specify a valid file type! ' . $_SERVER['HTTP_HOST'] . '/?file&type=[binary|json|text]',
              'status' => 400
            )))
          );
      }
    } else {
      header('HTTP/1.1 400 Bad Request');
      $this->sendOutput(
        json_encode(new CustomResponse(array(
          'message' => 'You did not specify a file type! ' . $_SERVER['HTTP_HOST'] . '/?file&type=[binary|json|text]',
          'status' => 400
        )))
      );
    }
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
          if ($choice == 0) {
            $this->sendOutput(
              json_encode(array(
                'response_type' => 'in_channel',
                'text' => 'Hey, just listening to _' . $this->songs[rand(0, count($this->songs) - 1)] . '_ right now. It *rules*!'
              ))
            );
          } else {
            $this->sendOutput(
              json_encode(array(
                'response_type' => 'in_channel',
                'text' => $this->responses[rand(0, count($this->responses) - 1)]
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

}