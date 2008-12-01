<?php 
class mail
{
	function checkEmailAddress($email) 
	{
		// First, we check that there's one @ symbol, and that the lengths are right
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) 
			return false;	// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		// Split it into sections to make life easier
		$emailArray = explode("@", $email);
		$localArray = explode(".", $emailArray[0]);
		for ($i = 0; $i < sizeof($localArray); $i++) 
			if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $localArray[$i])) 
				return false;
		if (!ereg("^\[?[0-9\.]+\]?$", $emailArray[1])) 
		{ 
			// Check if domain is IP. If not, it should be valid domain name
			$domainArray = explode(".", $emailArray[1]);
			if (sizeof($domainArray) < 2) 
				return false; // Not enough parts to domain
			for ($i = 0; $i < sizeof($domainArray); $i++) 
				if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domainArray[$i])) 
					return false;
		}
		return true;
	}
  
	function sendEmail($email,$body,$object)
	{
		$html = "MIME-Version: 1.0\r\n";
		$html .= "Content-type: text/html; charset=UTF-8\r\n";
		$html .= "From: FantaManajer <noreply@fantamanajer.it>\r\n";
		return @mail($email,$object, $body,$html);
	}
}
?>
