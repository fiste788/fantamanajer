<?php $j =0; $k = 0; $ruolo = ""; ?>
<h3>Giornata <?php echo $this->giornata; ?></h3>
<div id="stadio">
	<div id="campo">
		<div id="PP" class="droppable"></div>
		<div id="DD" class="droppable"></div>
		<div id="CC" class="droppable"></div>
		<div id="AA" class="droppable"></div>
	</div>
	<div id="panchina">
		<h3>Panchinari</h3>
		<?php for($i = 0;$i < 7;$i++): ?>
			<div id="panch-<?php echo $i; ?>" class="droppable"></div>
		<?php endfor; ?>
	</div>
</div>
<div id="capitani">
	<h3>Capitani</h3>
	<div id="cap-C" class="droppable"></div>
	<div id="cap-VC" class="droppable"></div>
	<div id="cap-VVC" class="droppable"></div>
</div>
<form action="<?php echo Links::getLink('formazione'); ?>" method="post">
	<fieldset id="titolari-field">

			<?php for($i = 0;$i < 11;$i++): ?>
				<input<?php if(isset($this->formazione->giocatori[$i]) && !empty($this->formazione->giocatori[$i])){ echo ' value="' . $this->formazione->giocatori[$i]->idGiocatore . '" title="' . $this->giocatori[$this->formazione->giocatori[$i]->idGiocatore]->ruolo . $this->giocatori[$this->formazione->giocatori[$i]->idGiocatore]->ruolo . '-' . $this->giocatori[$this->formazione->giocatori[$i]->idGiocatore]->cognome . ' ' . $this->giocatori[$this->formazione->giocatori[$i]->idGiocatore]->nome;if(file_exists(PLAYERSDIR . $this->formazione->giocatori[$i]->idGiocatore . '.jpg')) echo '-1"'; else echo '"';} ?> id="gioc-<?php echo $i; ?>" type="hidden" name="gioc[<?php echo $i; ?>]" />
			<?php endfor; ?>
	</fieldset>
	<fieldset id="panchina-field">
			<?php for($i = 11;$i < 18;$i++): ?>
				<input<?php if(isset($this->formazione->giocatori[$i]) && !empty($this->formazione->giocatori[$i])){ echo ' value="' . $this->formazione->giocatori[$i]->idGiocatore . '" title="' . $this->giocatori[$this->formazione->giocatori[$i]->idGiocatore]->ruolo . $this->giocatori[$this->formazione->giocatori[$i]->idGiocatore]->ruolo . '-' . $this->giocatori[$this->formazione->giocatori[$i]->idGiocatore]->cognome . ' ' . $this->giocatori[$this->formazione->giocatori[$i]->idGiocatore]->nome;if(file_exists(PLAYERSDIR . $this->formazione->giocatori[$i]->idGiocatore . '.jpg')) echo '-1"'; else echo '"';} ?> id="panchField-<?php echo ($i - 11); ?>" type="hidden" name="panch[<?php echo ($i - 11); ?>]" />
			<?php endfor; ?>
		</fieldset>
		<fieldset id="capitani-field">
			<input value="<?php echo $this->formazione->idCapitano; ?>" title="<?php if(isset($this->giocatori[$this->formazione->idCapitano])) { echo $this->formazione->giocatori[$this->formazione->idCapitano]->ruolo . $this->giocatori[$this->formazione->idCapitano]->ruolo . '-' . $this->giocatori[$this->formazione->idCapitano];if(file_exists(PLAYERSDIR . $this->formazione->idCapitano . '.jpg')) echo '-1';} ?>" id="C" type="hidden" name="C" />
			<input value="<?php echo $this->formazione->idVCapitano; ?>" title="<?php if(isset($this->giocatori[$this->formazione->idVCapitano])) { echo $this->formazione->giocatori[$this->formazione->idVCapitano]->ruolo . $this->giocatori[$this->formazione->idVCapitano]->ruolo . '-' . $this->giocatori[$this->formazione->idVCapitano];if(file_exists(PLAYERSDIR . $this->formazione->idVCapitano . '.jpg')) echo '-1';} ?>" id="VC" type="hidden" name="VC" />
			<input value="<?php echo $this->formazione->idVVCapitano; ?>" title="<?php if(isset($this->giocatori[$this->formazione->idVVCapitano])) { echo $this->formazione->giocatori[$this->formazione->idVVCapitano]->ruolo . $this->giocatori[$this->formazione->idVVCapitano]->ruolo . '-' . $this->giocatori[$this->formazione->idVVCapitano];if(file_exists(PLAYERSDIR . $this->formazione->idVVCapitano . '.jpg')) echo '-1';} ?>" id="VVC" type="hidden" name="VVC" />
		</fieldset>
		<fieldset>
		<?php if($_SESSION['datiLega']->jolly && (!$this->usedJolly || (isset($this->jolly) && $this->jolly == 1))): ?>
		<div class="column">
			<label for="jolly">Jolly:</label>
			<input type="checkbox" class="checkbox" name="jolly" id="jolly" <?php if(isset($this->jolly) && $this->jolly == 1) echo ' checked="checked"'; ?> />
		</div>
		<?php endif; ?>
		<?php if($this->giornata == GIORNATA): ?>
			<input name="submit" type="submit" class="btn-primary right" value="Invia" />
		<?php endif; ?>
	</fieldset>
</form>
<div id="giocatori">
	<h3>Rosa giocatori</h3>
	<?php foreach($this->giocatori as $key=>$val): ?>
		<?php if($val->ruolo != $ruolo && $ruolo != "") echo '</div>'; ?>
		<?php if($ruolo != $val->ruolo) echo '<div class="' . $val->ruolo . '">'; ?>
		<div class="draggable giocatore <?php echo $val->ruolo; ?>">
			<a class="hidden" rel="<?php echo $val->id; ?>"></a>
			<?php if(file_exists(PLAYERSDIR . $val->id . '.jpg')): ?>
				<img alt="<?php echo $val->id; ?>" height="50" src="<?php echo PLAYERSURL . $val->id; ?>.jpg" />
			<?php endif; ?>
			<p><?php echo $val->cognome . ' ' . $val->nome; ?></p>
		</div>
		<?php $ruolo = ($ruolo != $val->ruolo) ? $val->ruolo : $ruolo; ?>
	<?php $j++; endforeach; ?>
	</div>
</div>
<script type="text/javascript">
// <![CDATA[
	<?php if(!empty($this->modulo)): ?>
	var modulo = Array();
	modulo['PP'] = <?php echo $this->modulo[0]; ?>;
	modulo['DD'] = <?php echo $this->modulo[1]; ?>;
	modulo['CC'] = <?php echo $this->modulo[2]; ?>;
	modulo['AA'] = <?php echo $this->modulo[3]; ?>;
	<?php endif; ?>
	var edit = true;
	var imgsUrl = '<?php echo PLAYERSURL; ?>';
// ]]>
</script>
