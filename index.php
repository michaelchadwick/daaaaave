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

  // all requests must start with /api
  if ($uri[1] !== 'api') {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('message' => 'Dave says: Try using the actual api, dude. https://dave.codana.me/api'));
    exit();
  }

  // all requests must be GET or OPTIONS
  if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit();
  }

  # slack api call - return data to slack, exit

  if (isset($_GET['slack'])) {
    $dotenv->required('DAVE_SLACK_TOKEN');
    $tokenInt = getenv('DAVE_SLACK_TOKEN');
    $tokenExt = $_GET['token'];

    // did we get a token?
    if (isset($tokenExt)) {
      // token matches
      if ($tokenExt == $tokenInt) {
        echo json_encode(array(
          'response_type' => 'in_channel',
          'text' => $responses[rand(0, count($responses) - 1)]
        ));
      }
      // invalid token
      else {
        echo json_encode(array(
          'text' => 'Dave says: \'What? I didn\'t understand that, dude.\''
        ));
      }
    }
    // missing token
    else {
      echo json_encode(array(
        'text' => 'Dave says: \'Eh? I don\'t think I know you, buddy.\''
      ));
    }

    exit();
  }

  # regular api call

  // if file, check type and size, return file, exit
  if (isset($_GET['file'])) {
    if (isset($_GET['type'])) {
      $fileType = $_GET['type'];

      switch ($fileType) {
        case 'data':
          header('Content-Description: File Transfer');
          header('Content-Transfer-Encoding: binary');
          header('Content-Type: application/force-download');

          $size = (isset($_GET['size']) && $_GET['size'] >= 0) ? $_GET['size'] : 1;

          switch ($size) {
            case '10':
              $filePath = './assets/data/10mb';
              break;
            case '20':
              $filePath = './assets/data/20mb';
              break;
            case '50':
              $filePath = './assets/data/50mb';
              break;
            case '100':
              $filePath = './assets/data/100mb';
              break;
            case '1000':
              $filePath = './assets/data/1000mb';
              break;
            default:
              $filePath = './assets/data/1mb';
              break;
          }

          header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
          header('Content-Length: ' . filesize($filePath));
          flush(); // Flush system output buffer
          readfile($filePath);
          exit();
        case 'json':
          $size = (isset($_GET['size']) && $_GET['size'] >= 0) ? $_GET['size'] : 1;

          switch ($size) {
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
              $filePath = './assets/json/1.json';
              break;
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
          echo json_encode(array('message' => 'You did not specify a file type! https://dave.codana.me/api?file&type=[data|json|text]'));
          exit();
      }
    } else {
      header('HTTP/1.1 400 Bad Request');
      echo json_encode(array('message' => 'You did not specify a file type! https://dave.codana.me/api?file&type=[data|json|text]'));
      exit();
    }


  }

  // else, we are returning a json array of daves
  $daveArray = [];
  $daveCount = $DAVE_LIMIT_COUNT_DEFAULT;

  // grab potential filter and adjust amount of daves
  if (isset($_GET['dave']) || isset($_GET['daves'])) {
    $daveCount = isset($_GET['dave']) ? $_GET['dave'] : $_GET['daves'];

    if ($daveCount < 0) $daveCount = 0;
  }

  // build dave array
  for($i = 0; $i < $daveCount; $i++) {
    $daveArray[$i] = 'd' . (str_repeat('a', $i + 1)) . 've';
  }

  echo json_encode($daveArray);

  exit();
?>
