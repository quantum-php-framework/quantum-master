<?php

require_once(__DIR__."/../composer/vendor/autoload.php");

require_once (__DIR__."/../quantum/kernel/quantum.php");


error_reporting(E_ALL);

set_time_limit(0);

ini_set('memory_limit','-1');

//date_default_timezone_set('America/Chicago');
//alt();
$quantum = new Quantum();