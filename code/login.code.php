<?php
require(INCDIR . 'login.inc.php');

$auth = new Login();
if(!$_SESSION['logged']) {
	if(Request::getInstance()->has('username') && Request::getInstance()->has('password')) {
		$auth->remember = (Request::getInstance()->get('remember') == 'on');
		if(!$auth->doLogin(Request::getInstance()->get('username'),md5(Request::getInstance()->get('password'))))
			$message->warning("Errore nel login");
		//else
	//		header('Location: ' . str_replace('&amp;','&',Links::getLink('dettaglioSquadra',array('squadra'=>$_SESSION['idUtente']))));
	}
	elseif(isset($_COOKIE['auth_username']) && isset($_COOKIE['auth_key']))
		$auth->renewLogin($_COOKIE['auth_username'], $_COOKIE['auth_key']);
}else {
	if(Request::getInstance()->has('logout'))
		$auth->logout();
}
?>
