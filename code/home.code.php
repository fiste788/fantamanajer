<?php 
	require (INCDIR.'punteggi.inc.php');
	require (INCDIR.'squadra.inc.php');
	require(INCDIR.'articolo.inc.php');
	require(INCDIR.'emoticon.inc.php');
	require_once(INCDIR.'eventi.inc.php');

	
	$articoloObj = new articolo();
	$squadraObj = new squadra();
	$eventiObj = new eventi();
	$contenttpl->assign('squadre',$squadraObj->getElencoSquadre());
	$punteggiObj = new punteggi();
	$emoticonObj = new emoticon();
	$classifica = $punteggiObj->getAllPunteggi();
	$appo = $classifica;
	foreach($appo as $key=>$val)
	{
		array_pop($appo[$key]);
		$prevSum[$key] = array_sum($appo[$key]);
	} 
	foreach($classifica as $key=>$val)
		$sum[$key] = array_sum($classifica[$key]);
	arsort($prevSum);
	
	foreach($prevSum as $key=>$val)
		$indexPrevSum[] = $key;
	foreach($sum as $key=>$val)
		$indexSum[] = $key;
		
	foreach($indexSum as $key => $val)
	{
		if($val == $indexPrevSum[$key])
			$diff[] = 0;
		else
			$diff[] = (array_search($val,$indexPrevSum))- $key;
	}
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
