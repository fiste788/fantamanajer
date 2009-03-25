<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'conf-stampa-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Impostazioni lega</h2>
</div>
<div id="confStampa" class="main-content">
	<form id="impostazioni" action="<?php echo $this->linksObj->getLink('impostazioni') ?>" method="post">
		<div class="formbox">
			<label>Nome lega</label>
			<input type="input" name="nomeLega" maxlength="15" value="<?php echo $_SESSION['datiLega']['nomeLega'] ?>"/>
		</div>
		<div class="formbox">
			<label>Capitano doppio</label>
			<input type="radio" name="capitano" value="1"<?php if($_SESSION['datiLega']['capitano']) echo ' checked="checked"' ?>" />Si
			<input type="radio" name="capitano" value="0"<?php if(!$_SESSION['datiLega']['capitano']) echo ' checked="checked"' ?>" />No
		</div>
		<div class="formbox">
			<label>Numero max trasferimenti</label>
			<input type="input" name="numTrasferimenti"  value="<?php echo $_SESSION['datiLega']['numTrasferimenti'] ?>" />
		</div>
		<div class="formbox">
			<label>Numero max selezione giocatori</label>
			<input type="input" name="numSelezioni"  value="<?php echo $_SESSION['datiLega']['numSelezioni'] ?>" />
		</div>
	</form>
</div>
<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
	<?php if(isset($_SESSION['message']) && $_SESSION['message'][0] == 0): ?>
		<div id="messaggio" class="messaggio good column last">
			<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
			<span><?php echo $_SESSION['message'][1]; ?></span>
		</div>
		<script type="text/javascript">
		window.onload = (function(){
 			$("#messaggio").effect("pulsate", { times: 3 }, 1000);
			$("#messaggio").click(function () {
				$("div#messaggio").fadeOut("slow");
			});
 		});
		</script>
		<?php unset($_SESSION['message']); ?>
	<?php endif; ?>
		<?php if($_SESSION['logged'] == TRUE): ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		<?php endif; ?>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
