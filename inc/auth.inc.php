<?php

//Authentication scripts

//Login function
function login($username, $password)
{
	$q = "SELECT username, password FROM squadra WHERE username LIKE '".$username."';";
	$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR() . $q);
	$valore  = mysql_fetch_row($exe);
	if(($valore[0] == $username)&&($valore[1] == md5($password)))
		return TRUE;
	else
		return FALSE;
}

//Logout function
function logout()
{
	unset($_SESSION['userid']);
	unset($_SESSION['logged']);
	unset($_SESSION['usertype']);
	unset($_SESSION['idsquadra']);
	unset($_SESSION['nomeSquadra']);
	unset($_SESSION['nomeProprietario']);
	unset($_SESSION['email']);
	unset($_SESSION['modulo']);
}

?>
