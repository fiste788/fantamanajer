<?php 
require_once(INCDIR . 'punteggio.db.inc.php');
require_once(INCDIR . 'utente.db.inc.php');
require_once(INCDIR . 'articolo.db.inc.php');
require_once(INCDIR . 'evento.db.inc.php');
require_once(INCDIR . 'giornata.db.inc.php');
require_once(INCDIR . 'emoticon.inc.php');

$punteggioObj = new punteggio();
$utenteObj = new utente();
$articoloObj = new articolo();
$eventoObj = new evento();
$giornataObj = new giornata();
$emoticonObj = new emoticon();

$contenttpl->assign('dataFine',date_parse($giornataObj->getTargetCountdown()));
$contenttpl->assign('squadre',$utenteObj->getElencoSquadreByLega($_SESSION['legaView']));
$classifica = $punteggioObj->getAllPunteggiByGiornata($punteggioObj->getGiornateWithPunt(),$_SESSION['legaView']);
foreach($classifica as $key => $val)
	$sum[$key] = array_sum($classifica[$key]);
if((GIORNATA -1) != 0)
{
	$classificaPrec = $punteggioObj->getAllPunteggiByGiornata($punteggioObj->getGiornateWithPunt() - 1,$_SESSION['legaView']);
	foreach($classificaPrec as $key => $val)
		$prevSum[$key] = array_sum($classificaPrec[$key]);

	foreach($prevSum as $key => $val)
		$indexPrevSum[] = $key;
	foreach($sum as $key => $val)
		$indexSum[] = $key;
	
	foreach($indexSum as $key => $val)
	{
		if($val == $indexPrevSum[$key])
			$diff[$key] = 0;
		else
			$diff[$key] = (array_search($val,$indexPrevSum)) - $key;
	}
}
else
	foreach($classifica as $key => $val)
		$diff[$key] = 0;	
$contenttpl->assign('classifica',$sum);
$contenttpl->assign('differenza',$diff);
$articoloObj->setidlega($_SESSION['legaView']);
$articolo = $articoloObj->select($articoloObj,'=','*',0,1,'insertDate');
if($articolo != FALSE)
	foreach ($articolo as $key => $val)
		$articolo[$key]->text = $emoticonObj->replaceEmoticon($val->text,IMGSURL . 'emoticons/');
$contenttpl->assign('articoli',$articolo);
$eventi = $eventoObj->getEventi($_SESSION['legaView'],NULL,0,5);
$contenttpl->assign('eventi',$eventi);
?>
