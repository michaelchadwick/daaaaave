<?php

namespace Src\Controller;

use CustomResponse;
use Dotenv\Dotenv;

use Dave\Models\Binary;
use Dave\Models\Dave;
use Dave\Models\Http;
use Dave\Models\Json;
use Dave\Models\Sites;
use Dave\Models\Text;

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
        return new Binary($_GET['size']);
      }

      // e.g. /?dave
      // we are returning a json array of one dave
      if (isset($this->qsParams['dave'])) {
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'body' => new Dave(
              $this->qsParams['dave'],
              'single', 
              isset($_GET['bypass'])
            ),
            'error' => false
          )))
        );

        exit;
      }
      
      // e.g. /?daves=5
      // we are returning a json array of daves
      if (isset($this->qsParams['daves'])) {
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'body' => new Dave(
              $this->qsParams['daves'], 
              'multiple', 
              isset($_GET['bypass'])
            ),
            'error' => false
          )))
        );

        exit;
      }

      // e.g. /?http_code=0|2xx|3xx|4xx|5xx
      // if http code, return pre-scripted JSON object
      if (isset($this->qsParams['http_code'])) {
        $this->_processHttp($this->qsParams['http_code']);

        $resp = new Http($this->qsParams['http_code']);

        header('HTTP/1.1 ' . $resp->status . ($resp->error ? ' Bad Request' : ''));
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'error' => $resp->error,
            'message' => $resp->message,
            'status' => intval($resp->status),
          )))
        );
      }

      // e.g. /?json&size=5
      if (isset($this->qsParams['json'])) {
        return new Json($_GET['size']);
      }

      // e.g. /?sites
      if (isset($this->qsParams['sites'])) {
        $this->sendOutput(
          json_encode(new CustomResponse(array(
            'body' => new Sites(),
            'error' => false
          )))
        );

        exit;
      }

      // e.g. /?slack
      // if slack call, return data to slack
      if (isset($this->qsParams['slack'])) {
        $this->_processSlack();
      }

      // e.g. /?text&size=10
      if (isset($this->qsParams['text'])) {
        return new Text($_GET['size']);
      }
    } elseif ($requestMethod == 'OPTIONS') {
      header('HTTP/1.1 200');
      echo json_encode(new CustomResponse(array(
        'message' => 'Dave says: Current OPTIONS available - ?binary, ?dave(s), ?http_code, ?slack, ?text',
        'status' => 200
      )));
      exit;
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
}