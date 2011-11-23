<?php 
foreach($pages as $key=>$val) {
    
	if(isset($val['navbar']) && $val['roles'] <= $_SESSION['roles']) {
		if(isset($val['navbar']['main']))
			$entries[$val['navbar']['key']] = $val['navbar'];
		$entries[$val['navbar']['key']]['pages'][] = $key;
	}
}

$sort_arr = array();
foreach($entries as $uniqid => $row)
	foreach($row as $key => $value)
		$sort_arr[$key][$uniqid] = $value;
array_multisort($sort_arr['order'] , SORT_ASC , $entries);

$firePHP->group("Notifiche");
if ($_SESSION['logged']) {
	require_once(INCDBDIR . 'giocatore.db.inc.php');
	require_once(INCDBDIR . 'trasferimento.db.inc.php');
    require_once(INCDBDIR . 'formazione.db.inc.php');

    $formazione = Formazione::getFormazioneBySquadraAndGiornata($_SESSION['idUtente'],GIORNATA);
    if(empty($formazione))
        $notifiche[] = new Notify(Notify::LEVEL_MEDIUM,'Non hai ancora impostato la formazione per questa giornata',Links::getLink('formazione'));

    $giocatoriInattivi = Giocatore::getGiocatoriInattiviByIdUtente($_SESSION['idUtente']);
	if(!empty($giocatoriInattivi) && count(Trasferimento::getTrasferimentiByIdSquadra($_SESSION['idUtente'])) < $_SESSION['datiLega']->numTrasferimenti )
        $notifiche[] = new Notify(Notify::LEVEL_HIGH,'Un tuo giocatore non è più nella lista!',Links::getLink('trasferimenti'));
}
$firePHP->groupEnd();

$navbarTpl->assign('entries',$entries);
?>
