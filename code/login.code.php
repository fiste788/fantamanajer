<?php
//This page has not to be included into index.php pages array because it has to be required only in the
//page you want login to appear.


//if postdata exists
if( (isset($_POST['username'])) && (isset($_POST['password'])))
{
	if( (!empty($_POST['username'])) && (!empty($_POST['password'])) )
	{
		$formsObj = & new string($_POST['username']);											//create a new string object
		$formsObj->stringCleaner();																				//slashes added and special characters corrected
		if(login($_POST['username'],$_POST['password']))									//login
		{
			$q = "SELECT idUtente,nome,nomeProp,cognome,mail,amministratore,idLega FROM utente WHERE username='" . $_POST['username'] . "';";
			$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());
			$valore  = mysql_fetch_array($exe);
			$navbartpl->assign('loginok',$formsObj->string);
			$_SESSION['userid'] = $_POST['username'];
			$_SESSION['logged'] = TRUE;
			if($valore['amministratore'] == 2)
				$_SESSION['usertype'] = 'superadmin';
			elseif($valore['amministratore'] == 1)
				$_SESSION['usertype'] = 'admin';
			else
				$_SESSION['usertype'] = 'user';
			$_SESSION['idLega'] = $valore['idLega'];
			$_SESSION['idSquadra'] = $valore['idUtente'];
			$_SESSION['nomeSquadra'] = $valore['nome'];
			$_SESSION['nomeProprietario'] = $valore['nomeProp'] . " " . $valore['cognome'];
			$_SESSION['email'] = $valore['mail'];
		}
		else
			$layouttpl->assign('loginerror',"Errore nel login");
	}
	else
		$layouttpl->assign('loginerror',"Errore nel login");
}
else
{
	if( (isset($_GET['logout'])) && ($_GET['logout'] == TRUE) )
		logout();
}
?>
