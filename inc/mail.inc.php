<?php 
class mail
{
	function checkEmailAddress($email) 
	{
		if(preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)+$/',$email))
			return true;
		else
    		return false; 
	}
  
	function sendEmail($email,$body,$object)
	{
		$html = "MIME-Version: 1.0\r\n";
		$html .= "Content-type: text/html; charset=UTF-8\r\n";
		$html .= "From: FantaManajer <noreply@fantamanajer.it>\r\n";
		$lista = explode(',',$email);
		if(is_array($lista))
		{
			$email = array_pop($lista);
			$html .= "Bcc: " . implode(',',$lista);		
		}
		return @mail($email,$object, $body,$html);
	}
	
	function sendEmailToVodafone($num,$body)
	{
		$html = "MIME-Version: 1.0\r\n";
		$html .= "Content-type: text/html; charset=UTF-8\r\n";
		$html .= "From: <stefano788@gmail.com>\r\n";
		return mail($num ."@sms.vodafone.it","bo", $body,$html);
	}
}
?>
