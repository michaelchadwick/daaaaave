<?php

define("PROJECT_ROOT_PATH", __DIR__ . "/../");

// include main configuration file
require_once PROJECT_ROOT_PATH . 'inc/config.php';

// include base controller file
require_once PROJECT_ROOT_PATH . 'src/Controller/BaseController.php';

// include our custom response class
require_once PROJECT_ROOT_PATH . 'src/Response/CustomResponse.php';

// require DotEnv to grab env vars
require_once PROJECT_ROOT_PATH . 'vendor/autoload.php';

?>
