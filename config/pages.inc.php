<?php
/**
 * Creo un array in cui specifico titolo,js e css dell'head
 */
 
$pages = array();
	$pages['home'] = array(	'title'=>"Home",
									'js'=>array('jquery'=>'jquery','ui'=>array('ui-core','ui-tabs','effects-core','effects-pulsate'),'countdown'=>'countdown'),
									'css'=>array('screen'),
									'roles'=>-1,
									'navbar'=>array('key'=>'home','title'=>'Home','order'=>1,'main'=>TRUE));
									
	$pages['squadre'] = array(	'title'=>"Squadra", 
								'js'=>array('jquery'=>'jquery','fancybox'=>'fancybox'),
								'css'=>array('screen'),
								'roles'=>-1,
								'navbar'=>array('key'=>'squadre','title'=>'Le squadre','order'=>3,'main'=>TRUE));
								
	$pages['classifica'] = array(	'title'=>"Classifica",
								'js'=>array('jquery'=>'jquery','flot'=>array('ie|excanvas','jquery-flot'),'custom'=>'classifica'),
								'css'=>array('screen'),
								'roles'=>-1,
								'navbar'=>array('key'=>'classifica','title'=>'Classifica','order'=>5,'main'=>TRUE));
								
	$pages['conferenzeStampa'] = array(	'title'=>"Conferenze stampa",
								'js'=>array('jquery'=>'jquery'),
								'css'=>array('screen'),
								'roles'=>-1,
								'navbar'=>array('key'=>'conferenzeStampa','title'=>'Conferenze stampa','order'=>4,'main'=>TRUE));
								
	$pages['altro'] = array(	'title'=>"Altro...",
								'js'=>'',
								'css'=>array('screen'),
								'roles'=>-1,
								'navbar'=>array('key'=>'altro','title'=>'Altro...','order'=>6,'main'=>TRUE));
								
	$pages['dettaglioSquadra'] = array(	'title'=>"Squadra", 
								'js'=>array('jquery'=>'jquery','fancybox'=>'fancybox'),
								'css'=>array('screen'),
								'roles'=>-1,
								'navbar'=>array('key'=>'dettaglioSquadra','title'=>'La tua squadra','order'=>2,'main'=>TRUE));
								
	$pages['areaAmministrativa'] = array(	'title'=>"Area amministrativa",
								'js'=>array('jquery'=>'jquery'),
								'css'=>array('screen'),
								'roles'=>1,
								'navbar'=>array('key'=>'areaAmministrativa','title'=>'Area amministrativa','order'=>7,'main'=>TRUE));
								
	$pages['dettaglioGiocatore'] = array(	'title'=>"Dettaglio giocatore", 
								'js'=>array('jquery'=>'jquery','flot'=>array('ie|excanvas','jquery-flot')),
								'css'=>array('screen'),
								'roles'=>-1,
								'navbar'=>array('key'=>'altro','title'=>'Dettaglio giocatore'));
									
	$pages['dettaglioGiornata'] = array(	'title'=>"Dettaglio punteggi",
									'js'=>'',
									'css'=>array('screen'),
									'roles'=>-1,
									'navbar'=>array('key'=>'classifica','title'=>'Dettaglio giornata'));
									
	$pages['premi'] = array(	'title'=>"Premi",
									'js'=>'',
									'css'=>array('screen'),
									'roles'=>-1,
									'navbar'=>array('key'=>'altro','title'=>'Premi'));
									
	$pages['contatti'] = array(	'title'=>"Contatti",
									'js'=>'',
									'css'=>array('screen'),
									'roles'=>-1,
									'navbar'=>array('key'=>'altro','title'=>'Contatti'));
									
	$pages['sendMail'] = array(	'title'=>"Invio mail formazioni",
									'js'=>'',
									'css'=>array('screen'),
									'roles'=>-1,
									'navbar'=>array('key'=>'altro','title'=>'Script'));
	
	$pages['acquistaGioc'] = array(	'title'=>"Acquista giocatori",
										'js'=>'',
										'css'=>array('screen'),
										'roles'=>-1,
										'navbar'=>array('key'=>'altro','title'=>'Script'));
										
	$pages['backup'] = array(	'title'=>"Backup",
									'js'=>'',
									'css'=>array('screen'),
									'roles'=>-1,
									'navbar'=>array('key'=>'altro','title'=>'Script'));
	
	$pages['weeklyScript'] = array(	'title'=>"Calcolo punteggi",
										'js'=>'',
										'css'=>array('screen'),
										'roles'=>-1,
										'navbar'=>array('key'=>'altro','title'=>'Script'));
										
	$pages['updateGioc'] = array(	'title'=>"Aggiorna lista giocatori",
										'js'=>'',
										'css'=>array('screen'),
										'roles'=>-1,
										'navbar'=>array('key'=>'altro','title'=>'Script'));
									
	$pages['feed'] = array(	'title'=>"Vedi gli eventi",
										'js'=>'',
										'css'=>array('screen'),
										'roles'=>-1,
										'navbar'=>array('key'=>'altro','title'=>'Eventi'));

	$pages['formazione'] = array(	'title'=>"Formazione", 
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen'),
										'roles'=>0,
										'navbar'=>array('key'=>'altro','title'=>'Formazione'));
										
	$pages['formazioneNew2'] = array(	'title'=>"Formazione", 
										'js'=>array('jquery'=>'jquery','ui'=>array('ui-core','effects-core','effects-pulsate','ui-draggable','ui-droppable')),
										'css'=>array('screen'),
										'roles'=>0,
										'navbar'=>array('key'=>'altro','title'=>'Formazione'));
									
	$pages['trasferimenti'] = array(	'title'=>"Trasferimenti",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen'),
										'roles'=>0,
										'navbar'=>array('key'=>'altro','title'=>'Trasferimenti'));
										
	$pages['giocatoriLiberi'] = array(	'title'=>"Giocatori liberi",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen'),
										'roles'=>0,
										'navbar'=>array('key'=>'altro','title'=>'Giocatori liberi'));
										
	$pages['altreFormazioni'] = array(	'title'=>"Altre formazioni",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen'),
										'roles'=>0,
										'navbar'=>array('key'=>'altro','title'=>'Altre formazioni'));
										
	$pages['modificaConferenza'] = array(	'title'=>"Crea o modifica conferenza",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen'),
										'roles'=>0,
										'navbar'=>array('key'=>'conferenzeStampa','title'=>'Crea o modifica conferenza'));
							
	$pages['download'] = array(	'title'=>"Area Download",
										'js'=>array('jquery'=>'jquery'),
										'css'=>array('screen'),
										'roles'=>0,
										'navbar'=>array('key'=>'altro','title'=>'Download'));
										
	$pages['creaSquadra'] = array(	'title'=>"Crea una nuova squadra",
										'js'=>array('jquery'=>'jquery','ui'=>array('ui-core','ui-dialog','effects-core','effects-pulsate')),
										'css'=>array('screen'),
										'roles'=>1,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Crea una nuova squadra'));
										
	$pages['nuovoTrasferimento'] = array(	'title'=>"Nuovo trasferimento",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen'),
										'roles'=>1,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Nuovo trasferimento'));
										
	$pages['inserisciFormazione'] = array(	'title'=>"Inserisci formazione mancante",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen'),
										'roles'=>1,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Gestione formazioni'));
										
	$pages['newsletter'] = array(	'title'=>"Newsletter",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen'),
										'roles'=>1,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Newsletter'));

	$pages['penalita'] = array(	'title'=>"Penalità",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen'),
										'roles'=>1,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Penalità'));
										
	$pages['impostazioni'] = array(	'title'=>"Impostazioni lega",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen'),
										'roles'=>1,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Impostazioni lega'));
									
	$pages['gestioneDatabase'] = array(	'title'=>"Gestione database",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen'),
										'roles'=>2,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Gestione database'));

	$pages['lanciaScript'] = array(	'title'=>"Lancia script",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen'),
										'roles'=>2,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Lancia script'));
										
	$pages['modificaGiocatore'] = array(	'title'=>"Modifica giocatore",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate'),'flot'=>array('ie|excanvas','jquery-flot')),
										'css'=>array('screen'),
										'roles'=>2,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Modifica giocatore'));
										
	$pages['giornate'] = array(	'title'=>"Giornate",
										'js'=>array('jquery'=>'jquery','ui'=>array('effects-core','effects-pulsate')),
										'css'=>array('screen'),
										'roles'=>2,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Giornate'));
										
?>