<?php
//This page has not to be included into index.php pages array because it has to be required only in the
//page you want login to appear.


//if postdata exists
if( (isset($_POST['username'])) && (isset($_POST['password'])))
{
	if( (!empty($_POST['username'])) && (!empty($_POST['password'])) )
	{
		$formsObj = new string($_POST['username']);											//create a new string object
		$formsObj->stringCleaner();																				//slashes added and special characters corrected
		if(login($_POST['username'],$_POST['password']))									//login
		{
			$q = "SELECT idUtente,nome,nomeProp,cognome,mail,amministratore,idLega FROM utente WHERE username='" . $_POST['username'] . "';";
			$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());
			$values  = mysql_fetch_object($exe);
			$navbartpl->assign('loginok',$formsObj->string);
			$_SESSION['userid'] = $_POST['username'];
			$_SESSION['logged'] = TRUE;
			$_SESSION['roles'] = $valore->amministratore;
			if($values->amministratore == 2)
				$_SESSION['usertype'] = 'superadmin';
			elseif($values->amministratore == 1)
				$_SESSION['usertype'] = 'admin';
			else
				$_SESSION['usertype'] = 'user';
			$_SESSION['idLega'] = $values->idLega;
			$_SESSION['legaView'] = $values->idLega;
			$_SESSION['idSquadra'] = $values->idUtente;
			$_SESSION['nomeSquadra'] = $values->nome;
			$_SESSION['nomeProprietario'] = $values->nomeProp . " " . $values->cognome;
			$_SESSION['email'] = $values->mail;
		}
	}
	else
		$message->warning("Errore nel login");
}
else
{
	if( (isset($_GET['logout'])) && ($_GET['logout'] == TRUE) )
		logout();
}
?>
