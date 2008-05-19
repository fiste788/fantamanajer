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
$width_large_thumb = 800;
$height_large_thumb = 600;
$image_type = 1;
switch($image_type) 
{
	case 1: $exts = '.jpg'; break;		// the last argument indicate the compression of the image
	case 2: $exts = '.gif'; break;
	case 3: $exts = '.png'; break;
	default:die("Parametro mancante o errato");
}
if (isset ($_FILES ['userfile']['tmp_name']))
{
	$ext = $uploadObj -> getExtension($_FILES ['userfile']['name']);
	if(isset($_SESSION['idsquadra']))
		$name = $_SESSION['idsquadra'];
	switch( $uploadObj -> uploadFile ($size , $img , $vid , $doc, $path , $name.'-temp'))
	{
			case 0: 	if($uploadObj -> resize($name , $path , $width_thumb , $height_thumb , $path.$name.'-temp.'.$ext, $image_type) && $uploadObj -> resize($name.'-small' , $path , $width_small_thumb , $height_small_thumb , $path.$name.'-temp.'.$ext, $image_type) && $uploadObj -> resize($name.'-med' , $path , $width_med_thumb , $height_med_thumb , $path.$name.'-temp.'.$ext, $image_type) && $uploadObj -> resize($name.'-original' , $path , $width_large_thumb , $height_large_thumb , $path.$name.'-temp.'.$ext, $image_type) && $uploadObj -> createReflex( $path . $name . '-small'.$exts,'56 56 56',$path . $name . '-small-reflex',$image_type) && $uploadObj -> createReflex( $path . $name . $exts ,'81 78 70',$path . $name . '-reflex',$image_type) && $uploadObj -> createReflex( $path . $name . '-med'.$exts,'81 78 70',$path . $name . '-med-reflex',$image_type))
			{
									$message[] = 0;
									$message[] = 'Upload effettuato correttamente, Potrebbe essere necessario ricaricare la pagina per problemi di cache. Premi Ctrl+R';
									unlink($path.$name.'-temp.'.$ext);
									}
								else
								{
									$message[] = 1;
									$message[] = 'Problemi nel ridimensionamento';
								}
								break;
			case 1: 	$message[] = 1;
							$message[] = 'Nessun file selezionato'; break;
			case 2: 	$message[] = 1;
							$message[] = 'File troppo grande'; break;
			case 3: 	$message[] = 1;
							$message[] = 'Tipo di file non supportato'; break;
			case 4: 	$message[] = 1;
							$message[] = 'Errore nell\'upload del file'; break;
	}
	$contenttpl->assign('message',$message);
}
?>
