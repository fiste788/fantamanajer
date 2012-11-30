<?php

require_once('../config/config.inc.php');
require(INCDIR . 'request.inc.php');

$request = Request::getInstance();
require_once(CODEDIR . 'login.code.php');

if ($_SESSION['logged']) {
    require(INCDIR . 'UploadHandler.php');

    $options = array(
        'filename' => $_SESSION['idUtente'] . '.jpg',
        'upload_dir' => UPLOADDIR,
        'upload_url' => UPLOADURL,
        'image_versions' => array(
            '' => array(
                'max_width' => 1920,
                'max_height' => 1200,
                'jpeg_quality' => 95
            ),
            'thumb' => array(
                'max_height' => 215,
                'max_width' => 1000,
                'jpeg_quality' => 80
            ),
            'thumb-small' => array(
                'max_width' => 1000,
                'max_height' => 93
            )
        )
    );
    $upload_handler = new UploadHandler($options);
}
?>