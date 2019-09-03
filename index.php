<?php
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

      // if requesting sample, check size
      if (isset($_GET['sample'])) {
        header('Cache-Control: must-revalidate');
        header('Content-Description: File Transfer');
        header('Content-Transfer-Encoding: binary');
        header('Content-Type: application/force-download');
        header('Expires: 0');
        header('Pragma: public');

        $sample = $_GET['sample'];

        switch ($sample) {
          case '100':
            $filepath = './sample100.json';
            break;
          case '1000':
            $filepath = './sample1000.json';
            break;
          case '10000':
            $filepath = './sample10000.json';
            break;
          default:
            $filepath = './sample100.json';
            break;
        }

        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Content-Length: ' . filesize($filepath));
        flush(); // Flush system output buffer
        readfile($filepath);
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
