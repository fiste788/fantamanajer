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
			<div class="formbox">
				<label>Pagina premi</label>
				<textarea name="premi"><?php if(!empty($_SESSION['datiLega']['premi'])) echo $_SESSION['datiLega']['premi']; ?></textarea>
			</div>
		</fieldset>
		<fieldset>
			<input type="submit" class="submit dark" value="OK" />
		</fieldset>
		<script type="text/javascript">
			mySettings['namespace'] = 'html';
			$(document).ready(function() {
	     		 $("textarea").markItUp(mySettings);
	  		 });
		</script>
	</form>
</div>