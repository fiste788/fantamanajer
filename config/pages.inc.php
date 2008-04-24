<?php
/**
 * Creo un array in cui specifico titolo,js e css dell'head
 */
 
$upages = array();
	$upages['home'] = array(	'title'=>"Home",
									'js'=>array('jquery'=>'jquery','ui'=>array('ui.base','ui.tabs','ui.tabs.ext')), 
									'css'=>array('screen','style','tabs'));
									
	$upages['rosa'] = array(	'title'=>"Squadra", 
								'js'=>array('jquery'=>'jquery','lightbox'=>'lightbox'),
								'css'=>array('screen','style','lightbox'));
								
	$upages['classifica'] = array(	'title'=>"Classifica",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$upages[] = 'punteggidettaglio';
	$upages[] = 'premi';
	$upages[] = 'weeklyScript';
	$upages[] = 'confStampa';
	$upages[] = 'sendMail';
	$upages[] = 'contatti';
	$upages[] = 'acquistaGioc';
	$upages[] = 'backup';
	$upages[] = 'other';

$apages = array();
	$apages['home'] = array(	'title'=>"Home", 
									'js'=>array('jquery'=>'jquery','ui'=>'tabs'), 
									'css'=>array('screen','style','tabs'));
									
	$apages['formazione'] = array(	'title'=>"Formazione", 
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen','style'));
	
	$apages['rosa'] = array(	'title'=>"Squadra",
								'js'=>array('jquery'=>array('jquery','jquery.dimension'),'lightbox'=>'lightbox','ui'=>array('ui.base','ui.accordion')),
								'css'=>array('screen','style','lightbox'));
								
	$apages['classifica'] = array(	'title'=>"Classifica", 
									'js'=>array('jquery'=>'jquery'),
									'css'=>array('screen','style'));
									
	$apages[] = 'punteggidettaglio';
	$apages[] = 'trasferimenti';
	$apages[] = 'premi';
	$apages[] = 'freeplayer';
	$apages[] = 'formazioniAll';
	$apages[] = 'confStampa';
	$apages[] = 'editArticolo';
	$apages[] = 'contatti';
	$apages[] = 'location';
	$upages[] = 'other';

//echo "<pre>".print_r($upages,1)."</pre>";
//echo "<pre>".print_r(array_keys($upages),1)."</pre>";

 
?>
