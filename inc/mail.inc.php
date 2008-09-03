<?php 
class mail
{
	function sendEmail($email,$body,$object)
	{
		$html = "MIME-Version: 1.0\r\n";
		$html .= "Content-type: text/html; charset=UTF-8\r\n";
		$html .= "From: FantaManajer <noreply@fantamanajer.it>\r\n";
		if(@mail($email,$object, $body,$html))	
			return TRUE;
		else
			return FALSE;
	}

}
?>
