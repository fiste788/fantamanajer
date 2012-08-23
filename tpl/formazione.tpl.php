<?php $j =0; $k = 0; $ruolo = ""; ?>
<?php if(!STAGIONEFINITA || $this->giornata != GIORNATA): ?>
<h3>Giornata <?php echo $this->giornata; ?></h3>
<div id="stadio">
	<div id="campo">
		<div id="P" class="droppable"></div>
		<div id="D" class="droppable"></div>
		<div id="C" class="droppable"></div>
		<div id="A" class="droppable"></div>
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
				<input<?php if(isset($this->formazione->giocatori[$i]) && !empty($this->formazione->giocatori[$i])) echo ' value="' . $this->formazione->giocatori[$i]->idGiocatore . '"'; ?> id="gioc-<?php echo $i; ?>" type="hidden" name="titolari[<?php echo $i; ?>]" />
			<?php endfor; ?>
		</fieldset>
		<fieldset id="panchina-field">
			<?php for($i = 11;$i < 18;$i++): ?>
				<input<?php if(isset($this->formazione->giocatori[$i]) && !empty($this->formazione->giocatori[$i])) echo ' value="' . $this->formazione->giocatori[$i]->idGiocatore . '"'; ?> id="panchField-<?php echo ($i - 11); ?>" type="hidden" name="panchinari[<?php echo ($i - 11); ?>]" />
			<?php endfor; ?>
		</fieldset>
		<fieldset id="capitani-field">
			<input value="<?php echo $this->formazione->idCapitano; ?>" id="C" type="hidden" name="C" />
			<input value="<?php echo $this->formazione->idVCapitano; ?>" id="VC" type="hidden" name="VC" />
			<input value="<?php echo $this->formazione->idVVCapitano; ?>" id="VVC" type="hidden" name="VVC" />
		</fieldset>
		<fieldset>
			<?php if($_SESSION['datiLega']->jolly && (!$this->usedJolly || (isset($this->jolly) && $this->jolly == 1))): ?>
			<div class="column">
				<label for="jolly">Jolly:</label>
				<input type="checkbox" class="checkbox" name="jolly" id="jolly" <?php if(isset($this->jolly) && $this->jolly == 1) echo ' checked="checked"'; ?> />
			</div>
			<?php endif; ?>
			<?php if($this->giornata == GIORNATA): ?>
				<input name="submit" type="submit" class="btn btn-primary right" value="Invia" />
			<?php endif; ?>
		</fieldset>
	</form>
	<div id="giocatori">
		<h3>Rosa giocatori</h3>
		<?php foreach($this->giocatori as $key=>$val): ?>
			<?php if($val->ruolo != $ruolo && $ruolo != "") echo '</div>'; ?>
			<?php if($ruolo != $val->ruolo) echo '<div class="ruoli ' . $val->ruolo . '">'; ?>
			<div id="<?php echo $val->id; ?>"  data-ruolo="<?php echo $val->ruolo; ?>" class="draggable giocatore <?php echo $val->ruolo; ?>">
				<?php if(file_exists(PLAYERSDIR . $val->id . '.jpg')): ?>
					<img alt="<?php echo $val->id; ?>" src="<?php echo PLAYERSURL . $val->id; ?>.jpg" />
				<?php endif; ?>
				<p><?php echo $val->cognome . ' ' . $val->nome; ?></p>
			</div>
			<?php $ruolo = ($ruolo != $val->ruolo) ? $val->ruolo : $ruolo; ?>
		<?php $j++; endforeach; ?>
		</div>
	</div>
	<div id="panchina">
		<h3>Panchinari</h3>
		<?php for($i = 0;$i < 7;$i++): ?>
			<div id="panch-<?php echo $i; ?>" class="droppable"></div>
		<?php endfor; ?>
	</div>
</div>

<script type="text/javascript">
// <![CDATA[
	<?php if(!empty($this->modulo)): ?>
	var modulo = Array();
	modulo['P'] = <?php echo $this->modulo[0]; ?>;
	modulo['D'] = <?php echo $this->modulo[1]; ?>;
	modulo['C'] = <?php echo $this->modulo[2]; ?>;
	modulo['A'] = <?php echo $this->modulo[3]; ?>;
	<?php endif; ?>
	var edit = true;
	var imgsUrl = '<?php echo PLAYERSURL; ?>';
// ]]>
</script>
<?php else: ?>
<p>La stagione è finita. Non puoi settare la formazione ora</p>
<?php endif; ?>
