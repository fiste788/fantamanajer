<?php

namespace Lib;

class Module {

    /**
     *
     * @var Module
     */
    private static $istance;

    /**
     *
     * @return Module
     */
    public static function getInstance($instance = NULL) {
        if (!self::$istance)
            self::$istance = $instance;
        return self::$istance;
    }

    final public static function __callStatic($chrMethod, $arrArguments) {
        $objInstance = self::$istance;
        return call_user_func_array(array($objInstance, $chrMethod), $arrArguments);
    }

}

?>
