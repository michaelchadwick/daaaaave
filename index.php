<?php
  require __DIR__ . '/vendor/autoload.php';
  require __DIR__ . '/lib/variables.php';

  $DAVE_LIMIT_COUNT_DEFAULT = 10;

  $dotenv = new Dotenv\Dotenv(__DIR__);
  $dotenv->load();
  $dotenv->required('DAVE_SLACK_TOKEN');

  $tokenInt = getenv('DAVE_SLACK_TOKEN');

  header('Access-Control-Allow-Origin: *');
  header('Content-type: application/json; charset=utf-8');

  // make sure it's a GET
  if($_SERVER['REQUEST_METHOD'] == 'GET') {
    // plain API call
    if(isset($_GET['api'])) {
      $daveArray = [];
      $daveCount = $DAVE_LIMIT_COUNT_DEFAULT;

      if (isset($_GET['error'])) {
        return new Exception('You want an exception? This is how you get an exception.');
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

      // if randomized, pick one dave amongst the many
      $random = isset($_GET['random']);
      if(isset($random) && $random > 0) {
        $index = rand(0, count($daveArray) - 1);
        $json = array(
          "text" => $daveArray[$index]
        );
      }
      // otherwise, return all the daves
      else {
        $json = $daveArray;
      }
    }
    // slack api call
    else if(isset($_GET['slack'])) {
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
        "link" => "?api=1"
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
