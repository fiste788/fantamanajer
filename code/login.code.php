<?php
//This page has not to be included into index.php pages array because it has to be required only in the
//page you want login to appear.
require(INCDBDIR . 'utente.db.inc.php');
$firePHP->log($_SESSION);
//if postdata exists
if($request->has('username') && $request->has('password'))
{
	if(Utente::login($request->get('username'),$request->get('password')))
	{
		$dettagliUtente = Utente::getSquadraByUsername($request->get('username'),0);
		$_SESSION['userid'] = $request->get('username');
		$_SESSION['logged'] = TRUE;
		$_SESSION['roles'] = $dettagliUtente->amministratore;
		switch($dettagliUtente->amministratore) {
			case 1: $_SESSION['usertype'] = 'admin';break;
			case 2: $_SESSION['usertype'] = 'superadmin';break;
			default: $_SESSION['usertype'] = 'user';
		}
		$_SESSION['idLega'] = $dettagliUtente->idLega;
		$_SESSION['legaView'] = $dettagliUtente->idLega;
		$_SESSION['idUtente'] = $dettagliUtente->id;
		$_SESSION['nomeSquadra'] = $dettagliUtente->nomeSquadra;
		$_SESSION['nomeProprietario'] = $dettagliUtente->nome . " " . $dettagliUtente->cognome;
		$_SESSION['email'] = $dettagliUtente->email;
	}
else
	$message->warning("Errore nel login");
}
else
{
	if($request->has('logout') && (boolean) $request->get('logout'))
		Utente::logout();
}
?>
