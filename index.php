<?php

require_once('config' . DIRECTORY_SEPARATOR . 'config.php');
require_once('config' . DIRECTORY_SEPARATOR . 'autoload.php');

date_default_timezone_set("Europe/Rome");

$request = new \Lib\Request();
$dispatcher = new \Lib\Dispatcher();
$response = $dispatcher->handle($request);
$response->sendResponse();
