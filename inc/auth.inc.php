<?php
//Authentication scripts

//Login function
function login($username, $password)
{
	$q = "SELECT username, password FROM utente WHERE username LIKE '".$username."';";
	$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR() . $q);
	$valore  = mysql_fetch_row($exe);
	if((strcasecmp($valore[0],$username) == 0 )&&($valore[1] == md5($password)))
		return TRUE;
	else
		return FALSE;
}

//Logout function
function logout()
{
	foreach($_SESSION as $key=>$val)
		unset($_SESSION[$key]);
}
?>