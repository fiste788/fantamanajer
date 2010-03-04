<?php
/**
 * Upload class v1.2
 * Author: Sonzogni Stefano
 * Date: 16-02-2007
 * 
 * Description:
 * class for upload a file image, video or document on a server
 * this class contains 2 function: uploadfile and resize
 *
 * Changelog:
 * 04-10-2007: added a new function argument path and the name of file
 *
 */

/**	
 * - uploadfile: this upload the file on the server
 *				argument: 	size - the maximum size that is allowed
 *							img  - the array that contains the mime of the file images allowed
 *							vid  - the array that contains the mime of the file videos allowed
 *							doc  - the array that contains the mime of the file documents allowed
 							path - the url where the file is upload. is if NULL the file is upload in the default folder
 							name - the name of the file
 *				return: 	1 for no file select
 *							2 for file dimension over the maximum allowed
 *							3 for file type not allowed
 *							4 & 5 for error on upload file(not permession or generic error)
 *				output:	no visual output
 */
							
class Upload	
{
	static function uploadFile ($size , $img , $vid , $doc , $upload_dir , $name)	
	{
			if (trim ($_FILES["userfile"] ["tmp_name"] == ""))	
			{
					return 1;
					die ("errore nessun file");
			} 
			$mime = array_merge ($img , array_merge ($vid , $doc));		//check dimension
			if ($_FILES["userfile"]["size"] > $size)	
			{
					return 2;
					die ("errore nella grandezza");
			}
			if (!in_array ($_FILES['userfile'] ['type'] , $mime))		//check type of file
			{
					return 3;
					die ("errore nel tipo");
			}
			if ($upload_dir == "")
			{	
				switch ($_FILES ['userfile'] ['type'])	
				{
						case in_array($_FILES['userfile']['type'],$img) : $upload_dir = "uploadimages/";break;
						case in_array($_FILES['userfile']['type'],$vid) : $upload_dir = "uploadvideos/";break;
						case in_array($_FILES['userfile']['type'],$doc) : $upload_dir = "uploaddocs/";break;
				}
			}
			if ($name == NULL)
			{
				$filename = $upload_dir . $_FILES ['userfile']['name'];
			}
			else
			{
				$extension = self::getExtension($_FILES ["userfile"] ["name"]);
				$filename = $name . '.' . $extension;
			}
			if (is_uploaded_file ($_FILES ["userfile"] ["tmp_name"]))		
			{
					$path = $upload_dir . $filename;
					move_uploaded_file ($_FILES ["userfile"] ["tmp_name"],  $path)
					or die("errore creazione: " . $path);
			} 
			else 
			{
					return 5;
			}
			return 0;
	}
	
	private static function getExtension($filename)
	{
		$fields = explode('.',$filename);
		return strtolower(end($fields));
	}
	
/**
* -resize: create a new image from than bigger image
*				argument: new_name - the new filename of image without extension
*								destination_path - the directory where the file is create after the resize
*								width_thumb & height_thumb - the new dimension of the image
*								source path - the path of original image
*								image_type - int value that rappresent the extension of image resized( 1 jpeg , 2 gif , 3 png)
*				return:	the image resized in the destinatio_path
*				output: no visual output
*/
			
	static function resize($new_name , $destination_path , $width_thumb , $height_thumb , $source_path , $image_type)
	{
			switch (strtolower(self::getExtension($source_path)))			//switch for get the extension
			{
					case 'jpg' : $image = imagecreatefromjpeg($source_path); break;
					case 'gif' : $image = imagecreatefromgif($source_path); break;
					case 'png' : $image = imagecreatefrompng($source_path); break;
					default : die("File non supportato");
			}		
			$width = imagesx ($image);
			$height = imagesy ($image);
			$scala = min ($width_thumb / $width , $height_thumb / $height);
			if ($scala < 1)
			{
					$new_width = round ($scala*$width);
					$new_height = round ($scala*$height);
					$image_temp = @imagecreatetruecolor ($new_width , $new_height) or die("Cannot Initialize new GD image stream");
					imagecopyresized ($image_temp , $image , 0 , 0 , 0 , 0 , $new_width , $new_height , $width , $height);
					imagedestroy ($image);
					$image = $image_temp;
			}
			switch($image_type) 
			{
					case 1: return imagejpeg ($image , $destination_path . $new_name . '.jpg' , 80);break;		// the last argument indicate the compression of the image
					case 2: return imagegif ($image , $destination_path . $new_name . '.gif'); break;
					case 3: return imagepng ($image , $destination_path . $new_name . '.png'); break;
					default:die("Parametro mancante o errato");
			}
	}

	static function createReflex($sourcePath,$gradientColor,$newName,$image_type)
	{
		$size = getimagesize($sourcePath);  
		switch (strtolower(self::getExtension($sourcePath)))			//switch for get the extension
		{
			case 'jpg' : $imgImport = imagecreatefromjpeg($sourcePath); break;
			case 'gif' : $imgImport = imagecreatefromgif($sourcePath); break;
			case 'png' : $imgImport = imagecreatefrompng($sourcePath); break;
			default : die("File non supportato");
		}		
		$imgName_w = $size[0];
		$imgName_h = $size[1];
		$gradientHeight = $imgName_h/100*40;  
		// Create new blank image with sizes.
		$background = imagecreatetruecolor($imgName_w, $gradientHeight);  
		$gradparts = explode(" ",$gradientColor); // get the parts of the  colour (RRR,GGG,BBB)
		$dividerHeight = 1;  
		$gradient_y_startpoint = $dividerHeight;
		$gdGradientColor=ImageColorAllocate($background,$gradparts[0],$gradparts[1],$gradparts[2]); 
		$newImage = imagecreatetruecolor($imgName_w, $imgName_h);
		for ($x = 0; $x < $imgName_w; $x++) 
		{
		    for ($y = 0; $y < $imgName_h; $y++)
		    {
			    imagecopy($newImage, $imgImport, $x, $imgName_h - $y - 1, $x, $y, 1, 1);
		    }
		}
		// Add it to the blank background image
		imagecopymerge ($background, $newImage, 0, 0, 0, 0, $imgName_w, $imgName_h, 100); 
		//create from a the image so we can use fade out.
		$gradient_line = imagecreatetruecolor($imgName_w, 1);
		// Next we draw a GD line into our gradient_line
		imageline ($gradient_line, 0, 0, $imgName_w, 0, $gdGradientColor);
		$i = 0;
		$transparency = 50; //from 0 - 100
		while ($i < $gradientHeight) //create line by line changing as we go
		{
			imagecopymerge ($background, $gradient_line, 0,$gradient_y_startpoint, 0, 0, $imgName_w, 1, $transparency);
			++$i;
			++$gradient_y_startpoint;
			if ($transparency == 100) 
			{
				$transparency = 100;
			}
			else 
			{
				// this will determing the height of the
				//reflection. The higher the number, the smaller the reflection. 
				//1 being the lowest(highest reflection)
				$k = ceil(100 / $imgName_h); 
				$transparency = $transparency + $k;
			}
		} 
		// Set the thickness of the line we're about to draw
		imagesetthickness ($background, $dividerHeight);
		// Draw the line - me do not likey the liney
		// imageline ($background, 0, 0, $imgName_w, 0, $gdGradientColor);
		switch($image_type) 
		{
			case 1: return imagejpeg($background, $newName . '.jpg', 80);break;		// the last argument indicate the compression of the image
			case 2: return imagegif ($background, $newName . '.gif'); break;
			case 3: return imagepng ($background, $newName . '.png'); break;
			default:die("Parametro mancante o errato");
		}
		imagedestroy($background);
		imagedestroy($gradient_line);
		imagedestroy($newImage);  
	}
}
?>
