<?php
require_once(INCDBDIR . 'punteggio.db.inc.php');
require_once(INCDBDIR . 'utente.db.inc.php');
require_once(INCDBDIR . 'articolo.db.inc.php');
require_once(INCDBDIR . 'evento.db.inc.php');
require_once(INCDBDIR . 'giocatore.db.inc.php');
require_once(INCDIR . 'emoticon.inc.php');

$giornata = Punteggio::getGiornateWithPunt();
$bestPlayer = NULL;
$bestPlayers = NULL;

if($giornata > 0) {
    foreach ($ruoli as $ruolo=>$val) {
        $bestPlayers[$ruolo] = Giocatore::getBestPlayerByGiornataAndRuolo($giornata,$ruolo);
        $bestPlayer[$ruolo] = array_shift($bestPlayers[$ruolo]);
    }
}

FirePHP::getInstance()->log(Articolo::getByIds(array(1,2,3)));
$articoli = Articolo::getLastArticoli(1);
if($articoli != FALSE)
	foreach ($articoli as $key => $val)
		$articoli[$key]->text = Emoticon::replaceEmoticon($val->testo,EMOTICONSURL);

$eventi = Evento::getEventi(NULL,NULL,0,5);

$contentTpl->assign('squadre',Utente::getByField('idLega',$_SESSION['legaView']));
$contentTpl->assign('giornata',$giornata);
$contentTpl->assign('bestPlayer',$bestPlayer);
$contentTpl->assign('bestPlayers',$bestPlayers);
$contentTpl->assign('articoli',$articoli);
$contentTpl->assign('eventi',$eventi);
?>