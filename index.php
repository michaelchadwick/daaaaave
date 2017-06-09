<?php
  header("Access-Control-Allow-Origin: *");
  if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['api'])) {
    header('Content-type: application/json');
    $json = array(
      "dave" => "YES!",
      "daave" => "YES!",
      "daaave" => "YES!",
      "daaaave" => "YES!",
      "daaaaave" => "YES!",
      "daaaaaave" => "YES!",
      "daaaaaaave" => "YES!",
    );
    echo json_encode($json);
  } else if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['slack'])) {
    if(isset($_GET['token']) && $_GET['token'] == 'lrq432zlxosmAbfsNNhhG6yc') {
      $responses = array(
        "Yeah, Dave is with you, man.",
        "Damn right Dave is down.",
        "*You got it!*",
        "*Hell yes!*",
        "Alive and all that jive.",
        "Dave has got your back.",
        "I'm on my way.",
        "Keep *rockin'*, and Dave will be there.",
        "Just call me _Daaaaaaave_.",
        "I'm feeling just the right amount of *Dave* today, buddy.",
        "You rang?"
      );
      $json = array(
        "response_type" => "in_channel",
        "text" => $responses[rand(0,count($responses)-1)]
      );
      header('Content-type: application/json');
      echo json_encode($json);
    }
  } else {
?>

<!doctype html>
<html>
<head>
  <title>Is Dave Still With Us?</title>
  <style>
    body, html {
      width: 100%;
      height: 100%;
      margin: 0 auto;
      text-align: center;
      font-size: 60px;
      font-family: Impact;
    }
    h1 {
      font-size: 1.5em;
      margin: 0;
      padding: 0;
    }
    a, a:active, a:visited {
      color: blue;
      font-size: 1em;
    }
    h2 {
      color: white;
      font-size: 4em;
    }
    #wrap {
      padding: 20px;
    }
    #findHim {
      text-transform: uppercase;
    }
    .hidden {
      height: 10px;
      display: block;
      width: 100%;
    }
  </style>
  <script>
    function makeSpace(lines) {
      var elem, elem2;
      while(lines > 0) {
        console.log(lines);
        elem = document.createElement('div');
        elem.classList.add('hidden');
        document.getElementById('wrap').appendChild(elem);
        lines--;
      }
    }
  </script>
</head>
<body>
  <div id="wrap">
    <h1>Is <strong style='color: red'>Dave</strong> Still With Us?</h1>
    <p><a href="#" id="findHim">Click here to find out!</a></p>
    <script>makeSpace(500);</script>
    <a name="daaaaave"></a>
    <script>makeSpace(30);</script>
    <h2>YES!</h2>
    <script>makeSpace(30);</script>
  </div>
  <script>
    document.getElementById('findHim').addEventListener('click', function(e) {
      e.preventDefault();
      document.body.style.backgroundColor = "purple";
      document.location = "/#daaaaave";
    });
  </script>
</body>
</html>
<?php
}
?>
