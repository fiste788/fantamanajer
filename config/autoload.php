<?php

spl_autoload_register(function ($className) {
    $appo = explode("\\", $className);
    $source = array_shift($appo);
    $class = array_pop($appo);
    if ($source == 'Fantamanajer') {
        include_once FULLPATH . 'app' . DS . strtolower(implode(DS, $appo)) . DS . $class . '.php';
    } else {
        $file = FULLPATH . strtolower($source) . DS . strtolower(implode(DS, $appo)) . DS . $class . '.php';
        if (file_exists($file)) {
            include_once $file;
        } else {
            $file = FULLPATH . 'vendor' . DS . $source . DS . $source . '.php';
            if(file_exists($file)) {
                include_once $file;
            }
        }
    }
});