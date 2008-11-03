<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'admin-area-big.png'; ?>" alt="Logo Squadre" />
	</div>
	<h2 class="column">Area amministrativa</h2>
</div>
<div id="other" class="main-content">
	<?php if($_SESSION['logged'] == TRUE): ?>
	<div class="box-squadra column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<h3>Gestione squadre</h3>	
			<img class="logo column last" alt="->" src="<?php echo IMGSURL.'rose-big.png'; ?>" title="Gestione Squadre" />
			<ul>
				<li><a href="<?php echo $this->linksObj->getLink('creaSquadra',array('a'=>'new','id'=>'0')); ?>">Crea squadra</a></li>
				<li><a href="<?php echo $this->linksObj->getLink('nuovoTrasferimento'); ?>">Nuovo trasferimento</a></li>
			</ul>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<div class="box-squadra column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<h3>Manutenzione</h3>	
			<img class="logo column last" alt="->" src="<?php echo IMGSURL.'freeplayer-other.png'; ?>" title="Giocatori liberi" />
			<ul>
				<li><a href="<?php echo $this->linksObj->getLink('lanciaScript'); ?>">Lancia script</a></li>
				<li><a href="<?php echo $this->linksObj->getLink('gestioneDatabase'); ?>">Gestione database</a></li>
			</ul>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<div class="box-squadra column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<h3><a href="<?php echo $this->linksObj->getLink('premi'); ?>">Premi</a></h3>
			<img class="logo column last" alt="->" src="<?php echo IMGSURL.'premi-other.png'; ?>" title="Premi" />
			<div>Consulta i premi che sono in palio per il vincitore e per i primi  quattro posti</div>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<?php if(TIMEOUT != NULL): ?>
	<div class="box-squadra column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<h3><a href="<?php echo $this->linksObj->getLink('formazione'); ?>">Formazione</a></h3>
			<img class="logo column last" alt="->" src="<?php echo IMGSURL.'formazione-other.png'; ?>" title="Formazione" />
			<div>Imposta la squadra per la prossima giornata o vedi quella dei tuoi avversari</div>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<?php endif; ?>
	<?php endif; ?>
	<div class="box-squadra column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<h3><a href="<?php echo $this->linksObj->getLink('contatti'); ?>">Contatti</a></h3>
			<img class="logo column last" alt="->" src="<?php echo IMGSURL.'contatti-other.png'; ?>" title="Contatti" />	
			<div>Chiedi maggiori informazioni agli sviluppatori del FantaManajer attraverso le mail</div>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<div class="box-squadra column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<h3><a target="_blank" href="http://trac2.assembla.com/fantamanajer/wiki">Progetto</a></h3>	
			<img class="logo column last" alt="->" src="<?php echo IMGSURL.'progetto-other.png'; ?>" title="Progetto" />
			<div>Per saperne di più sul FantaManajer, e per aiutarci a migliorarlo</div>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
</div>
	<?php if($_SESSION['logged'] == TRUE): ?>
	<div id="squadradett" class="column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<?php endif; ?>
