<?php
  global $dotenv;
  global $responses;
  global $DAVE_LIMIT_COUNT_DEFAULT;

  require 'bootstrap.php';

  header('Access-Control-Max-Age: 3600');
  header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
  header('Access-Control-Allow-Methods: GET, OPTIONS');
  header('Access-Control-Allow-Origin: *');
  header('Cache-Control: must-revalidate');
  header('Content-type: application/json; charset=UTF-8');
  header('Expires: 0');
  header('Pragma: public');

  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $uri = explode('/', $uri);

  // print_r($uri);
  // print_r($_SERVER);

  // all requests must be GET or OPTIONS
  if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'OPTIONS'])) {
    header('HTTP/1.1 405');
    echo json_encode(new CustomResponse(array(
      'message' => 'Dave says: Only got time for GETs and OPTIONSs, slick.',
      'status' => 405
    )));
    exit();
  }

  // all requests must start with /api
  if ($uri[1] !== 'api') {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(new CustomResponse(array(
      'message' => 'Dave says: Try using the actual api, dude. https://dave.codana.me/api',
      'status' => 400
    )));
    exit();
  }

  if (isset($_SERVER['QUERY_STRING'])) {
    // e.g. api/?slack
    // if slack call, return data to slack, exit
    if (isset($_GET['slack'])) {
      // did we get a Slack token?
      if (isset($_GET['token'])) {
        $tokenExt = $_GET['token'];

        $dotenv->required('DAVE_SLACK_TOKEN');
        $tokenInt = getenv('DAVE_SLACK_TOKEN');

        if (isset($tokenExt) && !empty($tokenInt)) {
          // token matches
          if ($tokenExt == $tokenInt) {
            echo json_encode(array(
              'response_type' => 'in_channel',
              'text' => $responses[rand(0, count($responses) - 1)]
            ));
            exit();
          } else { // invalid token
            echo json_encode(array(
              'text' => 'Dave says: \'What? I didn\'t understand that, dude.\''
            ));
            exit();
          }
        }
        // missing internal token
        else {
          echo json_encode(array(
            'text' => 'Dave says: \'Eh? I don\'t think I know you, buddy.\''
          ));
          exit();
        }
      } else { // missing external token
        echo json_encode(array(
          'text' => 'Dave says: \'Eh? I don\'t think I know you, buddy.\''
        ));
        exit();
      }
    }

    # regular api call

    // e.g. api/?file&type=data|json|text&size=1|10|100|1000
    // if file, check type and size, return file, exit
    if (isset($_GET['file'])) {
      if (isset($_GET['type'])) {
        $fileType = $_GET['type'];

        switch ($fileType) {
          case 'data':
            $size = (isset($_GET['size']) && $_GET['size'] >= 0) ? $_GET['size'] : 0;

            switch ($size) {
              case '1':
                $filePath = './assets/data/1mb_of_dave';
                break;
              case '10':
                $filePath = './assets/data/10mb_of_dave';
                break;
              case '20':
                $filePath = './assets/data/20mb_of_dave';
                break;
              case '50':
                $filePath = './assets/data/50mb_of_dave';
                break;
              case '100':
                $filePath = './assets/data/100mb_of_dave';
                break;
              case '1000':
                $filePath = './assets/data/1000mb_of_dave';
                break;
              default:
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(new CustomResponse(array(
                  'message' => 'Dave says: That size ain\'t here, man.',
                  'status' => 400
                )));
                exit();
            }

            header('Content-Description: File Transfer');
            header('Content-Transfer-Encoding: binary');
            header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));
            flush(); // Flush system output buffer
            readfile($filePath);
            exit();
          case 'json':
            $size = (isset($_GET['size']) && $_GET['size'] >= 0) ? $_GET['size'] : 1;

            switch ($size) {
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
              case '10000':
                $filePath = './assets/json/10000.json';
                break;
              default:
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(new CustomResponse(array(
                  'message' => 'Dave says: That size ain\'t here, man.',
                  'status' => 400
                )));
                exit();
            }

            echo file_get_contents($filePath);
            exit();
          case 'text':
            header('Content-Description: File Transfer');
            header('Content-Type: text/plain');

            $size = (isset($_GET['size']) && $_GET['size'] >= 0) ? $_GET['size'] : 1;

            switch ($size) {
              case '10':
                $filePath = './assets/text/10.txt';
                break;
              case '100':
                $filePath = './assets/text/100.txt';
                break;
              case '1000':
                $filePath = './assets/text/1000.txt';
                break;
              case '10000':
                $filePath = './assets/text/10000.txt';
                break;
              default:
                $filePath = './assets/text/1.txt';
                break;
            }

            header('Content-Length: ' . filesize($filePath));
            flush(); // Flush system output buffer
            echo file_get_contents($filePath);
            exit();
          default:
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(new CustomResponse(array(
              'message' => 'You did not specify a valid file type! https://dave.codana.me/api?file&type=[data|json|text]',
              'status' => 400
            )));
            exit();
        }
      } else {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(new CustomResponse(array(
          'message' => 'You did not specify a file type! https://dave.codana.me/api?file&type=[data|json|text]',
          'status' => 400
        )));
        exit();
      }
    }
  }

  // e.g. api/204
  // if http code, return pre-scripted JSON object, exit
  if (isset($uri[2])) {
    $code = $uri[2];

    switch ($code) {
      case 0:
        header('HTTP/1.1 500');
        echo json_encode(new CustomResponse(array(
          'message' => 'Dave says: I am nothing.',
          'status' => 500,
        )));
        exit();
      case 200:
        header('HTTP/1.1 200');
        echo json_encode(new CustomResponse(array(
          'error' => false,
          'message' => 'Dave says: Woo!'
        )));
        exit();
      case 204:
        header('HTTP/1.1 204');
        echo json_encode(new CustomResponse(array(
          'error' => false,
          'message' => 'Dave says: ...',
          'status' => 204
        )));
        exit();
      case 302:
        header('HTTP/1.1 302');
        echo json_encode(new CustomResponse(array(
          'error' => false,
          'message' => 'Dave says: I moved, man.',
          'status' => 302
        )));
        exit();
      case 400:
        header('HTTP/1.1 400');
        echo json_encode(new CustomResponse(array(
          'message' => 'Dave says: Bad to the bone, dude.',
          'status' => 400
        )));
        exit();
      case 403:
        header('HTTP/1.1 403');
        echo json_encode(new CustomResponse(array(
          'message' => 'Dave says: No way in.',
          'status' => 403
        )));
        exit();
      case 404:
        header('HTTP/1.1 404');
        echo json_encode(new CustomResponse(array(
          'message' => 'Dave says: I\'m not here, man.',
          'status' => 404
        )));
        exit();
      case 405:
        header('HTTP/1.1 405');
        echo json_encode(new CustomResponse(array(
          'message' => 'Dave says: I can\'t allow that here, guy.',
          'status' => 405
        )));
        exit();
      case 410:
        header('HTTP/1.1 410');
        echo json_encode(new CustomResponse(array(
          'status' => 410
        )));
        exit();
      case 444:
        header('HTTP/1.1 444');
        echo json_encode(new CustomResponse(array(
          'status' => 444
        )));
        exit();
      case 500:
        header('HTTP/1.1 500');
        echo json_encode(new CustomResponse(array(
          'status' => 500
        )));
        exit();
      case 502:
        header('HTTP/1.1 502');
        echo json_encode(new CustomResponse(array(
          'status' => 502
        )));
        exit();
      default:
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(new CustomResponse(array(
          'message' => 'Dave says: I don\'t get it?',
          'status' => 400
        )));
        exit();
    }
  }

  // if nothing else triggers, we are returning a json array of daves
  $daveArray = [];
  $daveCount = $DAVE_LIMIT_COUNT_DEFAULT;

  // grab potential filter and adjust amount of daves
  if (isset($_GET['dave']) || isset($_GET['daves'])) {
    $daveCount = isset($_GET['dave']) ? $_GET['dave'] : $_GET['daves'];

    if ($daveCount < 0) {
      $daveCount = 0;
    }
  }

  // build dave array
  for($i = 0; $i < $daveCount; $i++) {
    $daveArray[$i] = 'd' . (str_repeat('a', $i + 1)) . 've';
  }

  // return JSON of daves
  echo json_encode(new CustomResponse(array(
    'body' => $daveArray,
    'error' => false
  )));

  exit();
?>
<html>
<head>
  <title>Dave</title>
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/icons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/icons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/icons/favicon-16x16.png">
  <link rel="manifest" href="/assets/icons/site.webmanifest">
</head>
