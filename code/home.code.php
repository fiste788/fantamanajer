<?php 
	require (INCDIR.'punteggi.inc.php');
	require (INCDIR.'squadra.inc.php');
	require(INCDIR.'articolo.inc.php');
	
	$articoloObj = new articolo();
	$squadraObj = new squadra();
	$contenttpl->assign('squadre',$squadraObj->getElencoSquadre());
	$punteggiObj = new punteggi();
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
	
	$contenttpl->assign('articoli',$articoloObj->select($articoloObj,NULL,'*',0,2,'insertDate'));
?>
