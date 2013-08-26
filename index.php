<?php

require 'config' . DIRECTORY_SEPARATOR . 'config.php';
require CONFIGDIR . 'autoload.php';
require VENDORDIR . 'autoload.php';

date_default_timezone_set("Europe/Rome");

$request = new \Lib\Request();
$dispatcher = new \Lib\Dispatcher();
$response = $dispatcher->handle($request);
$response->sendResponse();

