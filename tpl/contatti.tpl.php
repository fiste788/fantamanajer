<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'contatti-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Contatti</h2>
</div>
<div  id="contatti" class="main-content">
	<p>Il progetto è stato sviluppato da Sonzogni Stefano e Francesco Bertocchi.<br />Se avete domande e volete ulteriori informazioni contattateci via mail ai seguenti indirizzi:<br /><br />
	Admin,Web-Designer,Web-Developer - <a href="mailto:sonzogni.stefano@gmail.com">sonzogni.stefano@gmail.com</a><br />
	Web-Developer - <a href="mailto:Det.ShaneVendrell@yahoo.it">Det.ShaneVendrell@yahoo.it</a><br/>
	</p>
</div>
<?php if($_SESSION['logged'] == TRUE): ?>
<div id="squadradett" class="messaggio column last">
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
