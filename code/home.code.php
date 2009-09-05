<?php 
require_once(INCDIR.'punteggi.inc.php');
require_once(INCDIR.'utente.inc.php');
require_once(INCDIR.'articolo.inc.php');
require_once(INCDIR.'emoticon.inc.php');
require_once(INCDIR.'eventi.inc.php');
require_once(INCDIR.'giornata.inc.php');

$articoloObj = new articolo();
$utenteObj = new utente();
$eventiObj = new eventi();
$punteggiObj = new punteggi();
$emoticonObj = new emoticon();
$giornataObj = new giornata();

$contenttpl->assign('dataFine',date_parse($giornataObj->getTargetCountdown()));
$contenttpl->assign('squadre',$utenteObj->getElencoSquadreByLega($_SESSION['legaView']));
$classifica = $punteggiObj->getAllPunteggiByGiornata($punteggiObj->getGiornateWithPunt(),$_SESSION['legaView']);
foreach($classifica as $key => $val)
	$sum[$key] = array_sum($classifica[$key]);
if((GIORNATA -1) != 0)
{
	$classificaPrec = $punteggiObj->getAllPunteggiByGiornata($punteggiObj->getGiornateWithPunt() - 1,$_SESSION['legaView']);
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
		$articolo[$key]['text'] = $emoticonObj->replaceEmoticon($val['text'],IMGSURL.'emoticons/');
$contenttpl->assign('articoli',$articolo);
$eventi = $eventiObj->getEventi($_SESSION['legaView'],NULL,0,5);
$contenttpl->assign('eventi',$eventi);
?>
