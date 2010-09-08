<?php
/**
 * Creo un array in cui specifico titolo,js e css dell'head
 */
 
$pages = array();
	$pages['home'] = array(	'title'=>"Home",
									'roles'=>-1,
									'navbar'=>array('key'=>'home','title'=>'Home','order'=>1,'main'=>TRUE));
									
	$pages['squadre'] = array(	'title'=>"Squadra", 
								'js'=>array('fancybox'=>'fancybox'),
								'roles'=>-1,
								'navbar'=>array('key'=>'squadre','title'=>'Le squadre','order'=>3,'main'=>TRUE));
								
	$pages['classifica'] = array(	'title'=>"Classifica",
								'ieHack'=>array('flot'=>'excanvas'),
								'js'=>array('flot'=>array('jquery.flot','jquery.flot.selection')),
								'roles'=>-1,
								'navbar'=>array('key'=>'classifica','title'=>'Classifica','order'=>5,'main'=>TRUE));
								
	$pages['conferenzeStampa'] = array(	'title'=>"Conferenze stampa",
								'roles'=>-1,
								'navbar'=>array('key'=>'conferenzeStampa','title'=>'Conferenze stampa','order'=>4,'main'=>TRUE));
								
	$pages['altro'] = array(	'title'=>"Altro...",
								'roles'=>-1,
								'navbar'=>array('key'=>'altro','title'=>'Altro...','order'=>6,'main'=>TRUE));
								
	$pages['dettaglioSquadra'] = array(	'title'=>"Squadra", 
								'js'=>array('fancybox'=>'fancybox'),
								'roles'=>-1,
								'navbar'=>array('key'=>'dettaglioSquadra','title'=>'La tua squadra','order'=>2,'main'=>TRUE));
								
	$pages['areaAmministrativa'] = array(	'title'=>"Area amministrativa",
								'roles'=>1,
								'navbar'=>array('key'=>'areaAmministrativa','title'=>'Area amministrativa','order'=>7,'main'=>TRUE));
								
	$pages['dettaglioGiocatore'] = array(	'title'=>"Dettaglio giocatore", 
								'ieHack'=>array('flot'=>'excanvas'),
								'js'=>array('flot'=>array('jquery.flot','jquery.flow.selection')),
								'roles'=>-1,
								'navbar'=>array('key'=>'altro','title'=>'Dettaglio giocatore'));
									
	$pages['dettaglioGiornata'] = array(	'title'=>"Dettaglio punteggi",
									'roles'=>-1,
									'navbar'=>array('key'=>'classifica','title'=>'Dettaglio giornata'));
									
	$pages['premi'] = array(	'title'=>"Premi",
									'roles'=>-1,
									'navbar'=>array('key'=>'altro','title'=>'Premi'));
									
	$pages['contatti'] = array(	'title'=>"Contatti",
									'roles'=>-1,
									'navbar'=>array('key'=>'altro','title'=>'Contatti'));
									
	$pages['sendMail'] = array(	'title'=>"Invio mail formazioni",
									'roles'=>-1,
									'navbar'=>array('key'=>'altro','title'=>'Script'));
	
	$pages['acquistaGioc'] = array(	'title'=>"Acquista giocatori",
										'roles'=>-1,
										'navbar'=>array('key'=>'altro','title'=>'Script'));
	
	$pages['uploadFtp'] = array(	'title'=>"Upload FTP",
										'roles'=>-1,
										'navbar'=>array('key'=>'altro','title'=>'Script','order'=>6));
										
	$pages['backup'] = array(	'title'=>"Backup",
									'roles'=>-1,
									'navbar'=>array('key'=>'altro','title'=>'Script'));
	
	$pages['minify'] = array(	'title'=>"Minify css and js",
									'roles'=>-1,
									'navbar'=>array('key'=>'altro','title'=>'Script'));
	
	$pages['updateOrariGiornata'] = array(	'title'=>"Aggiorna orari giornata corrente",
									'roles'=>-1,
									'navbar'=>array('key'=>'altro','title'=>'Script'));
	
	$pages['weeklyScript'] = array(	'title'=>"Calcolo punteggi",
										'roles'=>-1,
										'navbar'=>array('key'=>'altro','title'=>'Script'));
										
	$pages['updateGioc'] = array(	'title'=>"Aggiorna lista giocatori",
										'roles'=>-1,
										'navbar'=>array('key'=>'altro','title'=>'Script'));
									
	$pages['feed'] = array(	'title'=>"Vedi gli eventi",
										'roles'=>-1,
										'navbar'=>array('key'=>'altro','title'=>'Eventi'));

	$pages['formazioneBasic'] = array(	'title'=>"Formazione",
										'js'=>array('custom'=>'createFormazione'), 
										'roles'=>0,
										'navbar'=>array('key'=>'altro','title'=>'Formazione'));
										
	$pages['formazione'] = array(	'title'=>"Formazione", 
										'js'=>array('ui'=>array('jquery.ui.core','jquery.ui.widget','jquery.ui.mouse','jquery.ui.draggable','jquery.ui.droppable'),'custom'=>'createFormazione'),
										'roles'=>0,
										'navbar'=>array('key'=>'altro','title'=>'Formazione'));
									
	$pages['trasferimenti'] = array(	'title'=>"Trasferimenti",
										'roles'=>0,
										'navbar'=>array('key'=>'altro','title'=>'Trasferimenti'));
										
	$pages['giocatoriLiberi'] = array(	'title'=>"Giocatori liberi",
										'roles'=>0,
										'navbar'=>array('key'=>'altro','title'=>'Giocatori liberi'));
										
	$pages['altreFormazioni'] = array(	'title'=>"Altre formazioni",
										'js'=>array('ui'=>array('jquery.ui.core','jquery.ui.widget','jquery.ui.mouse','jquery.ui.draggable','jquery.ui.droppable'),'custom'=>'createFormazione'),
										'roles'=>0,
										'navbar'=>array('key'=>'altro','title'=>'Altre formazioni'));
										
	$pages['modificaConferenza'] = array(	'title'=>"Crea o modifica conferenza",
										'roles'=>0,
										'navbar'=>array('key'=>'conferenzeStampa','title'=>'Crea o modifica conferenza'));
							
	$pages['download'] = array(	'title'=>"Area Download",
										'roles'=>0,
										'navbar'=>array('key'=>'altro','title'=>'Download'));
										
	$pages['creaSquadra'] = array(	'title'=>"Crea una nuova squadra",
										'js'=>array('ui'=>array('jquery.ui.core','jquery.ui.dialog')),
										'roles'=>1,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Crea una nuova squadra'));
										
	$pages['nuovoTrasferimento'] = array(	'title'=>"Nuovo trasferimento",
										'roles'=>1,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Nuovo trasferimento'));
										
	$pages['inserisciFormazione'] = array(	'title'=>"Inserisci formazione mancante",
										'roles'=>1,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Gestione formazioni'));
										
	$pages['newsletter'] = array(	'title'=>"Newsletter",
										'roles'=>1,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Newsletter'));

	$pages['penalita'] = array(	'title'=>"Penalità",
										'roles'=>1,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Penalità'));
										
	$pages['impostazioni'] = array(	'title'=>"Impostazioni lega",
										'roles'=>1,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Impostazioni lega'));
									
	$pages['gestioneDatabase'] = array(	'title'=>"Gestione database",
										'roles'=>2,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Gestione database'));

	$pages['lanciaScript'] = array(	'title'=>"Lancia script",
										'roles'=>2,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Lancia script'));
										
	$pages['modificaGiocatore'] = array(	'title'=>"Modifica giocatore",
										'ieHack'=>array('flot'=>'excanvas'),
										'js'=>array('flot'=>array('jquery.flot','jquery.flot.selection')),
										'roles'=>2,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Modifica giocatore'));
										
	$pages['giornate'] = array(	'title'=>"Giornate",
										'css'=>array('ui.datepicker'),
										'js'=>array('ui'=>array('jquery.ui.core','jquery.ui.datepicker')),
										'roles'=>2,
										'navbar'=>array('key'=>'areaAmministrativa','title'=>'Giornate'));								
?>
