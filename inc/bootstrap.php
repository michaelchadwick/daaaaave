<?php

define("PROJECT_ROOT_PATH", __DIR__ . "/../");

// include main configuration file
require_once PROJECT_ROOT_PATH . 'inc/config.php';

// include base controller file
require_once PROJECT_ROOT_PATH . 'src/Controller/BaseController.php';

// include our custom model classes
require_once PROJECT_ROOT_PATH . 'src/Models/Binary.php';
require_once PROJECT_ROOT_PATH . 'src/Models/Config.php';
require_once PROJECT_ROOT_PATH . 'src/Models/Dave.php';
require_once PROJECT_ROOT_PATH . 'src/Models/Http.php';
require_once PROJECT_ROOT_PATH . 'src/Models/Json.php';
require_once PROJECT_ROOT_PATH . 'src/Models/Sites.php';
require_once PROJECT_ROOT_PATH . 'src/Models/Text.php';
require_once PROJECT_ROOT_PATH . 'src/Models/Version.php';

// include our custom response class
require_once PROJECT_ROOT_PATH . 'src/Response/CustomResponse.php';

// require DotEnv to grab env vars
require_once PROJECT_ROOT_PATH . 'vendor/autoload.php';

header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: must-revalidate');
header('Content-type: application/json; charset=UTF-8');
header('Expires: 0');
header('Pragma: public');

// pass the request method to the DaveController
use Src\Controller\DaveController;

$config = [];
$controller = new DaveController();
$controller->processRequest();
