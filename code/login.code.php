<?php
require(INCDIR . 'login.inc.php');

$auth = new Login();
if(!$_SESSION['logged']) {
	if($request->has('username') && $request->has('password')) {
		$auth->remember = ($request->get('remember') == 'on');
		if(!$auth->doLogin($request->get('username'),md5($request->get('password'))))
			$message->warning("Errore nel login");
		//else
	//		header('Location: ' . str_replace('&amp;','&',Links::getLink('dettaglioSquadra',array('squadra'=>$_SESSION['idUtente']))));
	}
	elseif(isset($_COOKIE['auth_username']) && isset($_COOKIE['auth_key']))
		$auth->renewLogin($_COOKIE['auth_username'], $_COOKIE['auth_key']);
}else {
	if($request->has('logout') && (boolean) $request->get('logout'))
		$auth->logout();
}
?>
