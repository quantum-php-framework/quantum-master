<?php

require_once(__DIR__ ."/../../composer/vendor/autoload.php");

require_once (__DIR__ . "/../kernel/system/helpers/functions.php");

require_once (__DIR__ . "/../kernel/system/modules/kernel/Kernel.php");

require_once (__DIR__ . "/../kernel/system/modules/teleport/Teleport.php");


$teleport = new \Quantum\Teleport();
$teleport->init();