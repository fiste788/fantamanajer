<?php
/**
 * Creo un array in cui specifico titolo,js e css dell'head
 */
 
$upages = array();
	$upages['home'] = array(	'title'=>"Home",
									'js'=>array('jquery'=>'jquery','ui'=>array('ui-base','ui-tabs','ui-tabs-ext')), 
									'css'=>array('screen','style','tabs'));
									
	$upages['rosa'] = array(	'title'=>"Squadra", 
								'js'=>array('jquery'=>'jquery','lightbox'=>'lightbox'),
								'css'=>array('screen','style','lightbox'));
								
	$upages['classifica'] = array(	'title'=>"Classifica",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$upages['punteggidettaglio'] = array(	'title'=>"Dettaglio punteggi",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$upages['premi'] = array(	'title'=>"Premi",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$upages['confStampa'] = array(	'title'=>"Conferenze stampa",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$upages['contatti'] = array(	'title'=>"Contatti",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$upages['other'] = array(	'title'=>"Altro...",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$upages['sendMail'] = array(	'title'=>"Invio mail formazioni",
									'js'=>'',
									'css'=>array('screen','style'));
	
	$upages['acquistaGioc'] = array(	'title'=>"Acquista giocatori",
										'js'=>'',
										'css'=>array('screen','style'));
										
	$upages['backup'] = array(	'title'=>"Backup",
									'js'=>'',
									'css'=>array('screen','style'));
	
	$upages['weeklyScript'] = array(	'title'=>"Calcolo punteggi",
										'js'=>'',
										'css'=>array('screen','style'));
	
$apages = array();
	$apages['home'] = array(	'title'=>"Home", 
									'js'=>array('jquery'=>'jquery','ui'=>array('ui-base','ui-tabs','ui-tabs-ext')), 
									'css'=>array('screen','style','tabs'));
	
	$apages['rosa'] = array(	'title'=>"Squadra",
								'js'=>array('jquery'=>array('jquery','jquery-dimension'),'lightbox'=>'lightbox','ui'=>array('ui-base','ui-accordion')),
								'css'=>array('screen','style','lightbox'));
								
	$apages['classifica'] = array(	'title'=>"Classifica", 
									'js'=>array('jquery'=>'jquery'),
									'css'=>array('screen','style'));
									
	$apages['punteggidettaglio'] = array(	'title'=>"Dettaglio punteggi",
									'js'=>array('jquery'=>'jquery'),
									'css'=>array('screen','style'));
									
	$apages['premi'] = array(	'title'=>"Premi",
									'js'=>array('jquery'=>'jquery'),
									'css'=>array('screen','style'));
									
	$apages['formazione'] = array(	'title'=>"Formazione", 
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen','style'));
										
	$apages['confStampa'] = array(	'title'=>"Conferenze stampa",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen','style'));
									
	$apages['contatti'] = array(	'title'=>"Contatti",
									'js'=>array('jquery'=>'jquery'),
									'css'=>array('screen','style'));	
									
	$apages['other'] = array(	'title'=>"Altro...",
								'js'=>array('jquery'=>'jquery'),
								'css'=>array('screen','style'));	
								
	$apages['sendMail'] = array(	'title'=>"Invio mail formazioni",
									'js'=>'',
									'css'=>array('screen','style'));						
	
	$apages['acquistaGioc'] = array(	'title'=>"Acquista giocatori",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$apages['backup'] = array(	'title'=>"Backup",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$apages['weeklyScript'] = array(	'title'=>"Calcolo punteggi",
										'js'=>'',
										'css'=>array('screen','style'));
									
	$apages['trasferimenti'] = array(	'title'=>"Trasferimenti",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen','style'));
										
	$apages['freeplayer'] = array(	'title'=>"Giocatori liberi",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen','style'));
										
	$apages['formazioniAll'] = array(	'title'=>"Altre formazioni",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen','style'));
										
	$apages['editArticolo'] = array(	'title'=>"Crea o modifica conferenza",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen','style'));
	$apages[] = 'location';

//echo "<pre>".print_r($upages,1)."</pre>";
//echo "<pre>".print_r(array_keys($upages),1)."</pre>";

 
?>
