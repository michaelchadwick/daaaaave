<?php
require 'lib/custom.error.php';
require 'lib/variables.php';
require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = new DotEnv(__DIR__);
$dotenv->load();
