<?php
require 'lib/custom.response.php';
require 'lib/variables.php';
require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = new DotEnv(__DIR__);
$dotenv->load();
