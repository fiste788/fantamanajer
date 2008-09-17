<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'conf-stampa-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Link utili</h2>
</div>
<div id="confStampa" class="main-content">
<p>In questa pagina vogliamo ringraziare tutti i servizi/siti che permettono al FantaManajer di esistere.<br />Eccone la lista:</p>
<ul>
	<li>
		<a href="http://www.gazzetta.it">Gazzetta delle Sport</a>
		<p>Ci fornisce tutti i dati e i voti dei giocatori. Direi indispensabile</p>
	</li>
	<li>
		<a href="http://www.110mb.com">110mb</a>
		<p>110mb ci supporta con lo spazio web. Servizio non sempre impeccabile ma è gratuito</p>
	</li>
	<li>
		<a href="http://www.webperte.it">WebPerTe</a>
		<p>Da loro abbiamo comprato il dominio www.fantamanajer.it e l'anno prossimo prenderemo anche l'hosting</p>
	</li>
	<li>
		<a href="http://www.onlinecronjobs.com">Online cron jobs</a>
		<p>Altro servizio indispensabile che ci permette di eseguire le operazioni di calcolo in modo automatico. Veramente ottimo è anche gratuito</p>
	</li>
	<li>
		<a href="http://www.assembla.com">Assembla</a>
		<p>Assemble mette gratuitamente a disposizione del FantaManajer un servizio per gestire il progetto al meglio</p>
	</li>
	<li>
		<a href="http://www.jquery.com">jQuery</a>
		<p>Avete presente tutti quegli effetti come popup,trasparenze e grafici? Tutto merito di jQuery</p>
	</li>
	<li>
		<a href="http://www.darioghilardi.com">Darioghilardi.com</a>
		<p>Ci ha aiutato concedendoci il suo account di 110mb con già il servizio mail attivo</p>
	</li>
</ul>
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
