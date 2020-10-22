<?php

require_once(__DIR__ ."/../../composer/vendor/autoload.php");

require_once (__DIR__."/../kernel/system/modules/runtime/Runtime.php");

require_once (__DIR__ . "/../kernel/system/helpers/functions.php");
require_once (__DIR__ . "/../kernel/system/modules/kernel/InternalPathResolver.php");
require_once (__DIR__ . "/../kernel/system/modules/kernel/Autoloader.php");
require_once (__DIR__ . "/../kernel/system/modules/qubit/QubitBootstrap.php");

date_default_timezone_set('America/Chicago');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$bootstrap = new Quantum\Qubit\QubitBootstrap();
$bootstrap->initLoop();