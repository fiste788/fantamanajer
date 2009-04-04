<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'impostazioni-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Impostazioni lega</h2>
</div>
<div id="confStampa" class="main-content">
	<form id="impostazioni" action="<?php echo $this->linksObj->getLink('impostazioni') ?>" method="post">
		<fieldset>
			<div class="formbox">
				<label>Nome lega</label>
				<input type="input" name="nomeLega" maxlength="15" value="<?php echo $_SESSION['datiLega']['nomeLega'] ?>"/>
			</div>
			<div class="formbox">
				<label>Capitano doppio</label>
				<input type="radio" name="capitano" value="1"<?php if($_SESSION['datiLega']['capitano']) echo ' checked="checked"' ?>" />Si
				<input type="radio" name="capitano" value="0"<?php if(!$_SESSION['datiLega']['capitano']) echo ' checked="checked"' ?>" />No
				<?php if(isset($this->default['capitano'])): ?><small>Default: <?php if($this->default['capitano'] == 1) echo "Si"; else echo "No"; ?></small><?php endif; ?>
			</div>
			<div class="formbox">
				<label>Numero max trasferimenti</label>
				<input type="input" name="numTrasferimenti" value="<?php echo $_SESSION['datiLega']['numTrasferimenti'] ?>" />
				<?php if(isset($this->default['numTrasferimenti'])): ?><small>Default: <?php echo $this->default['numTrasferimenti'] ?></small><?php endif; ?>
			</div>
			<div class="formbox">
				<label>Numero max selezione giocatori</label>
				<input type="input" name="numSelezioni" value="<?php echo $_SESSION['datiLega']['numSelezioni'] ?>" />
				<?php if(isset($this->default['numSelezioni'])): ?><small>Default: <?php echo $this->default['numSelezioni'] ?></small><?php endif; ?>
			</div>
			<div class="formbox">
				<label>Minuti consegna anticipata formazione</label>
				<input type="input" name="minFormazione" value="<?php echo $_SESSION['datiLega']['minFormazione'] ?>" />
				<?php if(isset($this->default['minFormazione'])): ?><small>Default: <?php echo $this->default['minFormazione'] ?></small><?php endif; ?>
			</div>
		</fieldset>
		<fieldset>
			<input type="submit" class="submit dark" value="OK" />
		</fieldset>
	</form>
</div>
<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
	<?php if(isset($this->messaggio) && $this->messaggio[0] == 0): ?>
		<div id="messaggio" class="messaggio good column last">
			<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
			<span><?php echo $this->messaggio[1]; ?></span>
		</div>
	<?php elseif(isset($this->messaggio) && $this->messaggio[0] == 1): ?>
		<div id="messaggio" class="messaggio bad column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-bad-big.png'; ?>" />
			<span><?php echo $this->messaggio[1]; ?></span>
		</div>
	<?php endif; ?>
	<?php if(isset($this->messaggio)): ?>
		<script type="text/javascript">
		window.onload = (function(){
 			$("#messaggio").effect("pulsate", { times: 3 }, 1000);
			$("#messaggio").click(function () {
				$("div#messaggio").fadeOut("slow");
			});
 		});
		</script>
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
