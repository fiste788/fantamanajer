<?php

namespace Lib;

use AltoRouter;

class Router extends Module {


    /**
     *
     * @param AltoRouter $instance
     */
    public static function getInstance($instance = NULL) {
        parent::getInstance($instance);
    }

    /*public static function generate($routeName, array $params = array()) {
        return self::$router->generate($routeName, $params);
    }*/

 /*public static function __callStatic($name, $arguments) {
     \FirePHP::getInstance()->log($name);
     \FirePHP::getInstance()->log($arguments);
     return self::$router->$name($arguments);
 }*/

    /*
         * Passes on any static calls to this class onto the singleton PDO instance
         * @param $chrMethod, $arrArguments
         * @return $mix

        final public static function __callStatic( $chrMethod, $arrArguments ) {

            $objInstance = self::$router;

            return call_user_func_array(array($objInstance, $chrMethod), $arrArguments);

        } # end method

*/

}

 
