<?php

function __autoload($className) {
    $appo = explode("\\", $className);
    //echo $className . "<br>";
    $source = array_shift($appo);
    $class = array_pop($appo);
    if ($source == 'Fantamanajer') {
        
            include_once FULLPATH . 'app' . DS . strtolower(implode(DS, $appo)) . DS . $class . '.php';
        }
    else {
        $file = FULLPATH . strtolower($source) . DS . strtolower(implode(DS, $appo)) . DS . $class . '.php';
        if(file_exists($file))
            include_once $file;
    }
}

?>