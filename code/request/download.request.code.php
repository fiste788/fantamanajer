<?php

include_once(INCDIR . 'createZip.inc.php');

if (Request::getInstance()->has('giornata') && Request::getInstance()->get('giornata') != "" && Request::getInstance()->has('type')) {

    if (Request::getInstance()->get('type') == 'csv')
        $path = VOTICSVDIR;
    else
        $path = VOTIXMLDIR;
    FirePHP::getInstance()->log($path);
    if (Request::getInstance()->get('giornata') == "all") {
        $createZip = new createZip();
        $path = $createZip->createZipFromDir($path, 'voti' . strtoupper(Request::getInstance()->get('type')));
        $createZip->forceDownload($path, "voti" . strtoupper(Request::getInstance()->get('type')) . ".zip");
        @unlink($path);
    } else {
        header("Content-type: text/csv");
        header("Content-Disposition: attachment;filename=" . basename(Request::getInstance()->get('giornata')));
        header("Content-Transfer-Encoding: binary");
        header("Expires: 0");
        header("Pragma: no-cache");
        readfile($path . Request::getInstance()->get('giornata'));
    }
    die();
}
?>
