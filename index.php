<?php
  global $responses;

  require __DIR__ . '/vendor/autoload.php';
  require __DIR__ . '/lib/variables.php';

  $DAVE_LIMIT_COUNT_DEFAULT = 10;

  $dotenv = new Dotenv\Dotenv(__DIR__);
  $dotenv->load();

  header('Access-Control-Allow-Origin: *');
  header('Content-type: application/json; charset=utf-8');

  // make sure it's a GET
  if($_SERVER['REQUEST_METHOD'] == 'GET') {
    // plain API call
    if(isset($_GET['api'])) {
      // if file, check type
      if (isset($_GET['file'])) {
        header('Cache-Control: must-revalidate');
        header('Content-Description: File Transfer');
        header('Content-Transfer-Encoding: binary');
        header('Content-Type: application/force-download');
        header('Expires: 0');
        header('Pragma: public');

        if (isset($_GET['type'])) {
          $fileType = $_GET['type'];

          switch ($fileType) {
            case 'data':
              $size = $_GET['size'];

              switch ($size) {
                case '100':
                  $filePath = './assets/data/100mb';
                  break;
                case '1000':
                  $filePath = './assets/data/1000mb';
                  break;
                default:
                  $filePath = './assets/data/10mb';
                  break;
              }
              break;
            case 'json':
              $size = $_GET['size'];

              switch ($size) {
                case '1000':
                  $filePath = './assets/json/1000.json';
                  break;
                case '10000':
                  $filePath = './assets/json/10000.json';
                  break;
                default:
                  $filePath = './assets/json/100.json';
                  break;
              }
              break;
            case 'text':
              $size = $_GET['size'];

              switch ($size) {
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
                  $filePath = './assets/text/10.txt';
                  break;
              }
              break;
          }
        }

        $filePath = './assets/oops.txt';

        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        flush(); // Flush system output buffer
        readfile($filePath);
        exit;
      }

      $daveArray = [];
      $daveCount = $DAVE_LIMIT_COUNT_DEFAULT;

      if (isset($_GET['error'])) {
        return new ResponseError(ResponseError::STATUS_INTERNAL_SERVER_ERROR, 'You want an error? This is how you get an error.');
      }

      // optionally limit number of daves returned
      if (isset($_GET['daves'])) {
        if ($_GET['daves'] > 0) {
          $daveCount = $_GET['daves'];
        }
      }

      // build dave array
      for($i = 0; $i < $daveCount; $i++) {
        $daveArray[$i] = 'd' . (str_repeat('a', $i + 1)) . 've';
      }

      $json = $daveArray;
    }
    // slack api call
    else if(isset($_GET['slack'])) {
      $dotenv->required('DAVE_SLACK_TOKEN');
      $tokenInt = getenv('DAVE_SLACK_TOKEN');
      $tokenExt = $_GET['token'];

      // did we get a token?
      if(isset($tokenExt)) {
        // token matches
        if($tokenExt == $tokenInt) {
          $json = array(
            "response_type" => "in_channel",
            "text" => $responses[rand(0, count($responses) - 1)]
          );
        }
        // invalid token
        else {
          $json = array(
            "text" => "Dave says: 'What? I didn't understand that, dude.'"
          );
        }
      }
      // missing token
      else {
        $json = array(
          "text" => "Dave says: 'Eh? I don't think I know you, buddy.'"
        );
      }
    }
    // regular http request; needs to be api
    else {
      $json = array(
        "text" => "Dave says: 'Try the API instead, guy.'",
        "link" => "?api"
      );
    }
  } else {
    // some other HTTP request
    $json = array(
      "text" => "Dave says: 'I don't respond to that kind of talk, man.'"
    );
  }

  // return json of some sort
  echo json_encode($json);
?>
