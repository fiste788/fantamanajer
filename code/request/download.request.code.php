<?php

include_once(INCDIR . 'createZip.inc.php');

if ($request->has('giornata') && $request->get('giornata') != "" && $request->has('type')) {

    if ($request->get('type') == 'csv')
        $path = VOTICSVDIR;
    else
        $path = VOTIXMLDIR;
    FirePHP::getInstance()->log($path);
    if ($request->get('giornata') == "all") {
        $createZip = new createZip();
        $path = $createZip->createZipFromDir($path, 'voti' . strtoupper($request->get('type')));
        $createZip->forceDownload($path, "voti" . strtoupper($request->get('type')) . ".zip");
        @unlink($path);
    } else {
        header("Content-type: text/csv");
        header("Content-Disposition: attachment;filename=" . basename($request->get('giornata')));
        header("Content-Transfer-Encoding: binary");
        header("Expires: 0");
        header("Pragma: no-cache");
        readfile($path . $request->get('giornata'));
    }
    die();
}
?>
