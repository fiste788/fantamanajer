<?php
/**
 * Creo un array in cui specifico titolo,js e css dell'head
 */
 
$upages = array();
	$upages['home'] = array(	'title'=>"Home",
									'js'=>array('jquery'=>'jquery','ui'=>array('ui-base','ui-tabs','ui-tabs-ext','effects-core','effects-pulsate')),
									'css'=>array('screen','style','tabs'));
									
	$upages['rosa'] = array(	'title'=>"Squadra", 
								'js'=>array('jquery'=>'jquery','lightbox'=>'lightbox'),
								'css'=>array('screen','style','lightbox'));
								
	$upages['dettaglioGiocatore'] = array(	'title'=>"Dettaglio giocatore", 
								'js'=>array('jquery'=>'jquery','flot'=>array('ie|excanvas','jquery-flot')),
								'css'=>array('screen','style'));
								
	$upages['classifica'] = array(	'title'=>"Classifica",
									'js'=>array('jquery'=>'jquery','flot'=>array('ie|excanvas','jquery-flot')),
									'css'=>array('screen','style'));
									
	$upages['dettaglioGiornata'] = array(	'title'=>"Dettaglio punteggi",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$upages['premi'] = array(	'title'=>"Premi",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$upages['conferenzeStampa'] = array(	'title'=>"Conferenze stampa",
									'js'=>array('jquery'=>'jquery','pngfix'=>'jquery-pngFix'),
									'css'=>array('screen','style'));
									
	$upages['contatti'] = array(	'title'=>"Contatti",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$upages['altro'] = array(	'title'=>"Altro...",
									'js'=>'',
									'css'=>array('screen','style'));
						
	$upages['linkUtili'] = array(	'title'=>"Link Utili",
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
									
	$upages['feed'] = array(	'title'=>"Vedi gli eventi",
										'js'=>'',
										'css'=>array('screen','style'));
	
$apages = array();
	$apages['home'] = array(	'title'=>"Home", 
									'js'=>array('jquery'=>'jquery','ui'=>array('ui-base','ui-tabs','ui-tabs-ext')),
									'css'=>array('screen','style','tabs'));
	
	$apages['rosa'] = array(	'title'=>"Squadra",
								'js'=>array('jquery'=>array('jquery','jquery-dimension'),'lightbox'=>'lightbox','ui'=>array('ui-base','ui-accordion','effects-core','effects-pulsate')),
								'css'=>array('screen','style','lightbox'));
								
	$apages['dettaglioGiocatore'] = array(	'title'=>"Dettaglio giocatore", 
								'js'=>array('jquery'=>'jquery','flot'=>array('ie|excanvas','jquery-flot')),
								'css'=>array('screen','style'));
								
	$apages['classifica'] = array(	'title'=>"Classifica", 
									'js'=>array('jquery'=>'jquery','flot'=>array('ie|excanvas','jquery-flot')),
									'css'=>array('screen','style'));
									
	$apages['dettaglioGiornata'] = array(	'title'=>"Dettaglio punteggi",
									'js'=>array('jquery'=>'jquery'),
									'css'=>array('screen','style'));
									
	$apages['premi'] = array(	'title'=>"Premi",
									'js'=>array('jquery'=>'jquery'),
									'css'=>array('screen','style'));
									
	$apages['formazione'] = array(	'title'=>"Formazione", 
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));
										
	$apages['conferenzeStampa'] = array(	'title'=>"Conferenze stampa",
										'js'=>array('jquery'=>'jquery','pngfix'=>'ie|jquery-pngFix','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));
									
	$apages['contatti'] = array(	'title'=>"Contatti",
									'js'=>array('jquery'=>'jquery'),
									'css'=>array('screen','style'));	
									
	$apages['altro'] = array(	'title'=>"Altro...",
								'js'=>array('jquery'=>'jquery'),
								'css'=>array('screen','style'));	
								
	$apages['linkUtili'] = array(	'title'=>"Link Utili",
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
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));
										
	$apages['giocatoriLiberi'] = array(	'title'=>"Giocatori liberi",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen','style'));
										
	$apages['altreFormazioni'] = array(	'title'=>"Altre formazioni",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen','style'));
										
	$apages['modificaConferenza'] = array(	'title'=>"Crea o modifica conferenza",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));
										
	$apages['feed'] = array(	'title'=>"Vedi gli eventi",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen','style'));
										
	//$adminpages = $apages;
																				
	$adminpages['areaAmministrativa'] = array(	'title'=>"Area amministrativa",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen','style'));
										
	$adminpages['creaSquadra'] = array(	'title'=>"Crea una nuova squadra",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));
										
	$adminpages['nuovoTrasferimento'] = array(	'title'=>"Nuovo trasferimento",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));
										
	$adminpages['lanciaScript'] = array(	'title'=>"Lancia script",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));
										
	$adminpages['gestioneDatabase'] = array(	'title'=>"Gestione database",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));


//echo "<pre>".print_r($upages,1)."</pre>";
//echo "<pre>".print_r(array_keys($upages),1)."</pre>";

 
?>