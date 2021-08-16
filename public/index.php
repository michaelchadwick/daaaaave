<?php
  require '../inc/bootstrap.php';

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
    echo json_encode(new CustomResponse(array(
      'message' => 'Dave says: Try using the actual api, dude. https://dave.neb.host/api',
      'status' => 400
    )));
    exit();
  }

  // pass the request method to the DaveController:
  use Src\Controller\DaveController;
  $config = [];
  $controller = new DaveController();
  $controller->processRequest();
?>
<html>
<head>
  <title>Dave</title>
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/icons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/icons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/icons/favicon-16x16.png">
  <link rel="manifest" href="/assets/icons/site.webmanifest">
</head>
