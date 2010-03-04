<?php 
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'articolo.db.inc.php');
require_once(INCDIR . 'evento.db.inc.php');
require_once(INCDIR . 'giornata.db.inc.php');
require_once(INCDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'emoticon.inc.php');

$articoloObj = new articolo();

$ruo = array('P','D','C','A');
$giornata = Punteggio::getGiornateWithPunt();
foreach ($ruo as $ruolo)
	$bestPlayer[$ruolo] = Giocatore::getBestPlayerByGiornataAndRuolo($giornata,$ruolo);
$articolo = $articoloObj->select($articoloObj,NULL,'*',0,2,'insertDate');
if($articolo != FALSE)
	foreach ($articolo as $key => $val)
		$articolo[$key]->text = Emoticon::replaceEmoticon($val->text,EMOTICONSURL);
$eventi = Evento::getEventi(NULL,NULL,0,5);

$contentTpl->assign('dataFine',date_parse(Giornata::getTargetCountdown()));
$contentTpl->assign('squadre',Utente::getElencoSquadreByLega($_SESSION['legaView']));
$contentTpl->assign('giornata',$giornata);
$contentTpl->assign('bestPlayer',$bestPlayer);
$contentTpl->assign('articoli',$articolo);
$contentTpl->assign('eventi',$eventi);
?>
