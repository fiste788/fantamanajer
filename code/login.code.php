<?php
//This page has not to be included into index.php pages array because it has to be required only in the
//page you want login to appear.
require(INCDBDIR . 'utente.db.inc.php');

//if postdata exists
if( (isset($_POST['username'])) && (isset($_POST['password'])))
{
	if( (!empty($_POST['username'])) && (!empty($_POST['password'])) )
	{
		if(Utente::login($_POST['username'],$_POST['password']))
		{
			$dettagliUtente = Utente::getSquadraByUsername($_POST['username'],0);
			$_SESSION['userid'] = $_POST['username'];
			$_SESSION['logged'] = TRUE;
			$_SESSION['roles'] = $dettagliUtente->amministratore;
			if($dettagliUtente->amministratore == 2)
				$_SESSION['usertype'] = 'superadmin';
			elseif($dettagliUtente->amministratore == 1)
				$_SESSION['usertype'] = 'admin';
			else
				$_SESSION['usertype'] = 'user';
			$_SESSION['idLega'] = $dettagliUtente->idLega;
			$_SESSION['legaView'] = $dettagliUtente->idLega;
			$_SESSION['idUtente'] = $dettagliUtente->idUtente;
			$_SESSION['nomeSquadra'] = $dettagliUtente->nome;
			$_SESSION['nomeProprietario'] = $dettagliUtente->nomeProp . " " . $dettagliUtente->cognome;
			$_SESSION['email'] = $dettagliUtente->mail;
		}
	}
	else
		$message->warning("Errore nel login");
}
else
{
	if( (isset($_GET['logout'])) && ($_GET['logout'] == TRUE) )
		Utente::logout();
}
?>
