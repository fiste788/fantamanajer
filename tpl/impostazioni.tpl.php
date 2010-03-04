<form action="<?php echo Links::getLink('impostazioni'); ?>" method="post">
	<fieldset>
		<div class="formbox">
			<label>Nome lega</label>
			<input type="text" name="nomeLega" maxlength="15" value="<?php echo $_SESSION['datiLega']->nomeLega; ?>"/>
		</div>
		<div class="formbox">
			<label>Capitano doppio</label>
			<input type="radio" name="capitano" value="1"<?php echo ($_SESSION['datiLega']->capitano) ? ' checked="checked"' : ''; ?> />Si
			<input type="radio" name="capitano" value="0"<?php echo (!$_SESSION['datiLega']->capitano) ? ' checked="checked"' : ''; ?> />No
			<?php if(isset($this->default['capitano'])): ?><small>Default: <?php echo ($this->default['capitano'] == 1) ? "Si" : "No"; ?></small><?php endif; ?>
		</div>
		<div class="formbox">
			<label>Numero max trasferimenti</label>
			<input type="text" name="numTrasferimenti" value="<?php echo $_SESSION['datiLega']->numTrasferimenti; ?>" />
			<?php if(isset($this->default['numTrasferimenti'])): ?><small>Default: <?php echo $this->default['numTrasferimenti']; ?></small><?php endif; ?>
		</div>
		<div class="formbox">
			<label>Numero max selezione giocatori</label>
			<input type="text" name="numSelezioni" value="<?php echo $_SESSION['datiLega']->numSelezioni; ?>" />
			<?php if(isset($this->default['numSelezioni'])): ?><small>Default: <?php echo $this->default['numSelezioni']; ?></small><?php endif; ?>
		</div>
		<div class="formbox">
			<label>Minuti consegna anticipata formazione</label>
			<input type="text" name="minFormazione" value="<?php echo $_SESSION['datiLega']->minFormazione; ?>" />
			<?php if(isset($this->default['minFormazione'])): ?><small>Default: <?php echo $this->default['minFormazione']; ?></small><?php endif; ?>
		</div>
		<div class="formbox">
			<label>Percentuale sul punteggio della formazione precedente se si dimentica la formazione</label>
			<input type="text" name="punteggioFormazioneDimenticata" value="<?php echo $_SESSION['datiLega']->punteggioFormazioneDimenticata; ?>" />
			<?php if(isset($this->default['punteggioFormazioneDimenticata'])): ?><small>Default: <?php echo $this->default['punteggioFormazioneDimenticata']; ?></small><?php endif; ?>
		</div>
		<div class="formbox">
			<label>Pagina premi</label>
			<textarea rows="10" cols="50" name="premi"><?php echo (!empty($_SESSION['datiLega']->premi)) ? $_SESSION['datiLega']->premi : ''; ?></textarea>
		</div>
	</fieldset>
	<fieldset>
		<input type="submit" class="submit dark" value="OK" />
	</fieldset>
</form>
