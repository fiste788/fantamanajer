<?php

require_once('config' . DIRECTORY_SEPARATOR . 'config.php');
require_once('config' . DIRECTORY_SEPARATOR . 'routing.php');
require_once('config' . DIRECTORY_SEPARATOR . 'pages.php');
require_once('config' . DIRECTORY_SEPARATOR . 'autoload.php');

date_default_timezone_set("Europe/Rome");

$match = $router->match();
$target = explode('#', $match['target']);
$controller = $target[0];
$action = isset($target[1]) ? $target[1] : 'index';
$controllerName = '\Fantamanajer\Controllers\\' . ucfirst($controller) . "Controller";
//echo "<pre>" . print_r($pages,1) . "</pre>";


if (class_exists($controllerName)) {
    //FirePHP::getInstance()->log($controllerName);
    $loader = new $controllerName($controller, $action, $router, $match);
    $loader->setGeneralJs($generalJs);
    $loader->setGeneralCss($generalCss);
    $loader->initialize();
    if (method_exists($controllerName, $action)) {
        $loader->$action();

        echo $loader->render();
    }
    else
        die('unsopported method');
} else {
    die('unsopported controller');
}
?>
