<?php

namespace Lib;

class Module {

    /**
     *
     * @var Module
     */
    private static $instance;

    /**
     *
     * @return Module
     */
    public static function getInstance($instance = NULL) {
        if (!self::$instance) {
            self::$instance = $instance;
        }
        return self::$instance;
    }

    final public static function __callStatic($chrMethod, $arrArguments) {
        $objInstance = self::$instance;
        return call_user_func_array(array($objInstance, $chrMethod), $arrArguments);
    }

}

 
