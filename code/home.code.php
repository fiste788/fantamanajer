<?php 
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'articolo.db.inc.php');
require_once(INCDIR . 'evento.db.inc.php');
require_once(INCDIR . 'giornata.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'emoticon.inc.php');

$punteggioObj = new punteggio();
$utenteObj = new utente();
$articoloObj = new articolo();
$eventoObj = new evento();
$giornataObj = new giornata();
$giocatoreObj = new giocatore();
$emoticonObj = new emoticon();

$ruo = array('P','D','C','A');
$giornata = $punteggioObj->getGiornateWithPunt();
foreach ($ruo as $ruolo)
	$bestPlayer[$ruolo] = $giocatoreObj->getBestPlayerByGiornataAndRuolo($giornata,$ruolo);
$articolo = $articoloObj->select($articoloObj,NULL,'*',0,2,'insertDate');
if($articolo != FALSE)
	foreach ($articolo as $key => $val)
		$articolo[$key]->text = $emoticonObj->replaceEmoticon($val->text,EMOTICONSURL);
$eventi = $eventoObj->getEventi(NULL,NULL,0,5);

$contentTpl->assign('dataFine',date_parse($giornataObj->getTargetCountdown()));
$contentTpl->assign('squadre',$utenteObj->getElencoSquadreByLega($_SESSION['legaView']));
$contentTpl->assign('giornata',$giornata);
$contentTpl->assign('bestPlayer',$bestPlayer);
$contentTpl->assign('articoli',$articolo);
$contentTpl->assign('eventi',$eventi);
?>
