<?php 
	require_once(INCDIR.'punteggi.inc.php');
	require_once(INCDIR.'utente.inc.php');
	require_once(INCDIR.'articolo.inc.php');
	require_once(INCDIR.'emoticon.inc.php');
	require_once(INCDIR.'eventi.inc.php');

	$articoloObj = new articolo();
	$utenteObj = new utente();
	$eventiObj = new eventi();
	$punteggiObj = new punteggi();
	$emoticonObj = new emoticon();
	
	$contenttpl->assign('squadre',$utenteObj->getElencoSquadre());
	$classifica = $punteggiObj->getAllPunteggi($_SESSION['idLega']);
	foreach($classifica as $key=>$val)
		$sum[$key] = array_sum($classifica[$key]);
	if((GIORNATA -1) != 0)
	{
		$classificaPrec = $punteggiObj->getAllPunteggiByGiornata($punteggiObj->getGiornateWithPunt() - 1,$_SESSION['idLega']);
		foreach($classificaPrec as $key=>$val)
			$prevSum[$key] = array_sum($classificaPrec[$key]);
	
		foreach($prevSum as $key=>$val)
			$indexPrevSum[] = $key;
		foreach($sum as $key=>$val)
			$indexSum[] = $key;
		
		foreach($indexSum as $key => $val)
		{
			if($val == $indexPrevSum[$key])
				$diff[$key] = 0;
			else
				$diff[$key] = (array_search($val,$indexPrevSum))- $key;
		}
	}
	else
		foreach($classifica as $key => $val)
			$diff[$key] = 0;
			
	$contenttpl->assign('classifica',$sum);
	$contenttpl->assign('differenza',$diff);
	$articolo = $articoloObj->select($articoloObj,NULL,'*',0,1,'insertDate');
	if($articolo != FALSE)
		foreach ($articolo as $key=>$val)
			$articolo[$key]['text'] = $emoticonObj->replaceEmoticon($val['text'],IMGSURL.'emoticons/');
	$contenttpl->assign('articoli',$articolo);

	
	$eventi = $eventiObj->getEventi(NULL,0,5);
	$contenttpl->assign('eventi',$eventi);
	
?>