<?php
  require __DIR__ . '/vendor/autoload.php';

  $DAVE_LIMIT_COUNT_DEFAULT = 10;

  $dotenv = new Dotenv\Dotenv(__DIR__);
  $dotenv->load();
  $dotenv->required('DAVE_SLACK_TOKEN');

  $tokenInt = getenv('DAVE_SLACK_TOKEN');

  header('Access-Control-Allow-Origin: *');
  header('Content-type: application/json');

  // make sure it's a GET
  if($_SERVER['REQUEST_METHOD'] == 'GET') {
    // plain API call
    if(isset($_GET['api'])) {
      $daveArray = [];
      $daveLimitCount = $_GET['daves'];
      $daveCount = $DAVE_LIMIT_COUNT_DEFAULT;

      // optionally limit number of daves returned
      if (isset($daveLimitCount) && $daveLimitCount > 0) {
        $daveCount = $_GET['daves'];
      }

      // build dave array
      for($i = 0; $i < $daveCount; $i++) {
        $daveArray[$i] = 'd' . (str_repeat('a', $i + 1)) . 've';
      }

      // if randomized, pick one dave amongst the many
      $random = isset($_GET['random']);
      if(isset($random) && $random > 0) {
        $index = rand(0, count($daveArray) - 1);
        $json = $daveArray[$index];
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
      if(isset($token)) {
        // token matches
        if($tokenExt == $tokenInt) {
          $songs = array(
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
            'Humanity'
          );
          $responses = array(
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
            'Hey, just listening to _' . $songs[rand(0,count($songs)-1)] . '_ right now. It *rules*!'
          );
          $json = array(
            'response_type' => 'in_channel',
            'text' => $responses[rand(0, count($responses) - 1)]
          );
        }
        // invalid token
        else {
          $json = array(
            "message" => "Dave says: 'What? I didn't understand that, dude.'"
          );
        }
      }
      // missing token
      else {
        $json = array(
          "message" => "Dave says: 'Eh? I don't think I know you, buddy.'"
        );
      }
    }
    // regular http request; needs to be api
    else {
      $json = array(
        "message" => "Dave says: 'Try the API instead, guy.'",
        "link" => "?api=1"
      );
    }
  } else {
    // some other HTTP request
    $json = array(
      "message" => "Dave says: 'I don't respond to that kind of talk, man.'"
    );
  }

  // return json of some sort
  echo json_encode($json);
?>
