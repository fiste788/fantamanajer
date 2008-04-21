<?php
/* Upload v 1.0
author: Sonzogni Stefano*/
require (INCDIR.'upload.inc.php');		//import the class (upload)
$uploadObj = new upload();

//set type of file supported
$img = array ("image/gif" , "image/png" , "image/pjpeg" , "image/jpeg");
$vid = array ();
$doc = array ();
$size = 500000;		//set the max size for the upload file
$path = 'uploadimg/' ;
$width_thumb = 240;
$height_thumb = 300;
$width_small_thumb = 64;
$height_small_thumb = 48;
$width_med_thumb = 150;
$height_med_thumb = 75;
$image_type = 1;
$contenttpl -> assign ('isfileupload',-1);		//isfileupload( 0: upload ok	-	1: no file	-	2: size too much big	-	3: type of file error	-	4: error)
if (isset ($_FILES ['userfile']['tmp_name']))
{
	$ext = $uploadObj -> getExtension($_FILES ['userfile']['name']);
	if(isset($_SESSION['idsquadra']))
		$name = $_SESSION['idsquadra'];
	switch( $uploadObj -> uploadFile ($size , $img , $vid , $doc, $path , $name.'-temp'))
	{
			case 0: 	if($uploadObj -> resize($name , $path , $width_thumb , $height_thumb , $path.$name.'-temp.'.$ext, $image_type) && $uploadObj -> resize($name.'-small' , $path , $width_small_thumb , $height_small_thumb , $path.$name.'-temp.'.$ext, $image_type) && $uploadObj -> resize($name.'-med' , $path , $width_med_thumb , $height_med_thumb , $path.$name.'-temp.'.$ext, $image_type) && $uploadObj -> createReflex( $path . $name . '-small.jpg','56 56 56',$path . $name . '-small-reflex.jpg',1) && $uploadObj -> createReflex( $path . $name . '.jpg','81 78 70',$path . $name . '-reflex.jpg',1) && $uploadObj -> createReflex( $path . $name . '-med.jpg','81 78 70',$path . $name . '-med-reflex.jpg',1))
									$contenttpl -> assign ('isfileupload','Upload effettuato correttamente');
								else
									$contenttpl -> assign ('isfileupload','Problemi nel ridimensionamento');
								break;
			case 1: $contenttpl -> assign ('isfileupload','Nessun file selezionato'); break;
			case 2: $contenttpl -> assign ('isfileupload','File troppo grande'); break;
			case 3: $contenttpl -> assign ('isfileupload','Tipo di file non supportato'); break;
			case 4: $contenttpl -> assign ('isfileupload','Errore nell\'upload del file'); break;
	}
	rename($path.$name.'-temp.'.$ext,$path.$name.'-original.'.$ext);
}
?>
