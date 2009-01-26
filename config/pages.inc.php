<?php
/**
 * Creo un array in cui specifico titolo,js e css dell'head
 */
 
$guestpages = array();
	$guestpages['home'] = array(	'title'=>"Home",
									'js'=>array('jquery'=>'jquery','ui'=>array('ui-core','ui-tabs','ui-tabs-ext','effects-core','effects-pulsate')),
									'css'=>array('screen','style','tabs'));
									
	$guestpages['rosa'] = array(	'title'=>"Squadra", 
								'js'=>array('jquery'=>'jquery','pngfix'=>'jquery-pngFix','fancybox'=>'fancybox'),
								'css'=>array('screen','style','fancy'));
								
	$guestpages['dettaglioGiocatore'] = array(	'title'=>"Dettaglio giocatore", 
								'js'=>array('jquery'=>'jquery','flot'=>array('ie|excanvas','jquery-flot')),
								'css'=>array('screen','style'));
								
	$guestpages['classifica'] = array(	'title'=>"Classifica",
									'js'=>array('jquery'=>'jquery','flot'=>array('ie|excanvas','jquery-flot')),
									'css'=>array('screen','style'));
									
	$guestpages['dettaglioGiornata'] = array(	'title'=>"Dettaglio punteggi",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$guestpages['premi'] = array(	'title'=>"Premi",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$guestpages['conferenzeStampa'] = array(	'title'=>"Conferenze stampa",
									'js'=>array('jquery'=>'jquery'),
									'css'=>array('screen','style'));
									
	$guestpages['contatti'] = array(	'title'=>"Contatti",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$guestpages['altro'] = array(	'title'=>"Altro...",
									'js'=>'',
									'css'=>array('screen','style'));
						
	$guestpages['linkUtili'] = array(	'title'=>"Link Utili",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$guestpages['sendMail'] = array(	'title'=>"Invio mail formazioni",
									'js'=>'',
									'css'=>array('screen','style'));
	
	$guestpages['acquistaGioc'] = array(	'title'=>"Acquista giocatori",
										'js'=>'',
										'css'=>array('screen','style'));
										
	$guestpages['backup'] = array(	'title'=>"Backup",
									'js'=>'',
									'css'=>array('screen','style'));
	
	$guestpages['weeklyScript'] = array(	'title'=>"Calcolo punteggi",
										'js'=>'',
										'css'=>array('screen','style'));
									
	$guestpages['feed'] = array(	'title'=>"Vedi gli eventi",
										'js'=>'',
										'css'=>array('screen','style'));
	
$userpages = array();
	$userpages['home'] = array(	'title'=>"Home", 
									'js'=>array('jquery'=>'jquery','ui'=>array('ui-core','ui-tabs','ui-tabs-ext')),
									'css'=>array('screen','style','tabs'));
	
	$userpages['rosa'] = array(	'title'=>"Squadra",
								'js'=>array('jquery'=>'jquery','pngfix'=>'jquery-pngFix','fancybox'=>'fancybox','ui'=>array('ui-core','ui-accordion','effects-core','effects-pulsate'),'tooltip'=>'tooltip'),
								'css'=>array('screen','style','fancy'));
								
	$userpages['dettaglioGiocatore'] = array(	'title'=>"Dettaglio giocatore", 
								'js'=>array('jquery'=>'jquery','flot'=>array('ie|excanvas','jquery-flot')),
								'css'=>array('screen','style'));
								
	$userpages['classifica'] = array(	'title'=>"Classifica", 
									'js'=>array('jquery'=>'jquery','flot'=>array('ie|excanvas','jquery-flot')),
									'css'=>array('screen','style'));
									
	$userpages['dettaglioGiornata'] = array(	'title'=>"Dettaglio punteggi",
									'js'=>array('jquery'=>'jquery'),
									'css'=>array('screen','style'));
									
	$userpages['premi'] = array(	'title'=>"Premi",
									'js'=>array('jquery'=>'jquery'),
									'css'=>array('screen','style'));
									
	$userpages['formazione'] = array(	'title'=>"Formazione", 
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));
										
	$userpages['conferenzeStampa'] = array(	'title'=>"Conferenze stampa",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));
									
	$userpages['contatti'] = array(	'title'=>"Contatti",
									'js'=>array('jquery'=>'jquery'),
									'css'=>array('screen','style'));	
									
	$userpages['altro'] = array(	'title'=>"Altro...",
								'js'=>array('jquery'=>'jquery'),
								'css'=>array('screen','style'));	
								
	$userpages['linkUtili'] = array(	'title'=>"Link Utili",
									'js'=>array('jquery'=>'jquery'),
									'css'=>array('screen','style'));
								
	$userpages['sendMail'] = array(	'title'=>"Invio mail formazioni",
									'js'=>'',
									'css'=>array('screen','style'));
	
	$userpages['acquistaGioc'] = array(	'title'=>"Acquista giocatori",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$userpages['backup'] = array(	'title'=>"Backup",
									'js'=>'',
									'css'=>array('screen','style'));
									
	$userpages['weeklyScript'] = array(	'title'=>"Calcolo punteggi",
										'js'=>'',
										'css'=>array('screen','style'));
									
	$userpages['trasferimenti'] = array(	'title'=>"Trasferimenti",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));
										
	$userpages['giocatoriLiberi'] = array(	'title'=>"Giocatori liberi",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen','style'));
										
	$userpages['altreFormazioni'] = array(	'title'=>"Altre formazioni",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen','style'));
										
	$userpages['modificaConferenza'] = array(	'title'=>"Crea o modifica conferenza",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));
										
	$userpages['feed'] = array(	'title'=>"Vedi gli eventi",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen','style'));

$adminpages = array();																			
	$adminpages['areaAmministrativa'] = array(	'title'=>"Area amministrativa",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen','style'));
										
	$adminpages['creaSquadra'] = array(	'title'=>"Crea una nuova squadra",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));
										
	$adminpages['nuovoTrasferimento'] = array(	'title'=>"Nuovo trasferimento",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));
										
	$adminpages['inserisciFormazione'] = array(	'title'=>"Inserisci formazione mancante",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));
										
	$adminpages['newsletter'] = array(	'title'=>"Newsletter",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));

	$adminpages['penalita'] = array(	'title'=>"Penalità",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));

$superadminpages = array();										
	$superadminpages['gestioneDatabase'] = array(	'title'=>"Gestione database",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));

	$superadminpages['lanciaScript'] = array(	'title'=>"Lancia script",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen','style'));
										
//echo "<pre>".print_r($guestpages,1)."</pre>";
//echo "<pre>".print_r(array_keys($guestpages),1)."</pre>";
?>
