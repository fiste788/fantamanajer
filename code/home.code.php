<?php 
require_once(INCDBDIR . 'punteggio.db.inc.php');
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'articolo.db.inc.php');
require_once(INCDBDIR . 'evento.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'emoticon.inc.php');

$giornata = Punteggio::getGiornateWithPunt();
foreach ($ruoli as $ruolo=>$val)
	$bestPlayer[$ruolo] = Giocatore::getBestPlayerByGiornataAndRuolo($giornata,$ruolo);
$articoli = Articolo::getLastArticoli(2);
if($articoli != FALSE)
	foreach ($articoli as $key => $val)
		$articoli[$key]->text = Emoticon::replaceEmoticon($val->text,EMOTICONSURL);

$eventi = Evento::getEventi(NULL,NULL,0,5);

$contentTpl->assign('squadre',Utente::getByField('idLega',$_SESSION['legaView']));
$contentTpl->assign('giornata',$giornata);
$contentTpl->assign('bestPlayer',$bestPlayer);
$contentTpl->assign('articoli',$articoli);
$contentTpl->assign('eventi',$eventi);
?>