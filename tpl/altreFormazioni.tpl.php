<?php $j =0; $k = 0; $ruolo = ""; ?>
<?php if(isset($this->formazione)): ?>
<h3>Giornata <?php echo GIORNATA; ?></h3>
<form action="<?php echo $this->linksObj->getLink('formazione'); ?>" method="post">
	<fieldset class="no-margin no-padding">
		<div class="column last" style="width:564px">
			<div id="campo" class="column">
				<div id="PP" class="droppable"></div>
				<div id="DD" class="droppable"></div>
				<div id="CC" class="droppable"></div>
				<div id="AA" class="droppable"></div>
			</div>
		</div>
		<div id="capitani" class="column">
			<h3>Capitani</h3>
			<?php foreach ($this->elencoCap as $key => $val): ?>
				<div id="cap-<?php echo $val; ?>" class="droppable"></div>
			<?php endforeach; ?>
		</div>
		<div id="panchina" class="column" style="width:300px">
			<h3>Panchinari</h3>
			<?php for($i = 0;$i < 7;$i++): ?>
				<div id="panch-<?php echo $i; ?>" class="droppable"></div>
			<?php endfor; ?>
		</div>
		<div id="titolari-field">
			<?php for($i = 0;$i < 11;$i++): ?>
				<input<?php if(isset($this->titolari[$i]) && !empty($this->titolari[$i])){ echo ' value="' . $this->titolari[$i] . '" title="' . $this->giocatoriId[$this->titolari[$i]]->ruolo . $this->giocatoriId[$this->titolari[$i]]->ruolo . '-' . $this->giocatoriId[$this->titolari[$i]]->cognome . ' ' . $this->giocatoriId[$this->titolari[$i]]->nome;if(file_exists(IMGDIR . 'foto/' . $this->titolari[$i] . '.jpg')) echo '-1"'; else echo '"';} ?> id="gioc-<?php echo $i; ?>" type="hidden" name="gioc[<?php echo $i; ?>]" />
			<?php endfor; ?>
		</div>
		<div id="panchina-field">
			<?php for($i = 0;$i < 7;$i++): ?>
				<input<?php if(isset($this->panchinari[$i]) && !empty($this->panchinari[$i])){ echo ' value="' . $this->panchinari[$i] . '" title="' . $this->giocatoriId[$this->panchinari[$i]]->ruolo . $this->giocatoriId[$this->panchinari[$i]]->ruolo . '-' . $this->giocatoriId[$this->panchinari[$i]]->cognome . ' ' . $this->giocatoriId[$this->panchinari[$i]]->nome;if(file_exists(IMGDIR . 'foto/' . $this->panchinari[$i] . '.jpg')) echo '-1"'; else echo '"';} ?> id="panchField-<?php echo $i; ?>" type="hidden" name="panch[<?php echo $i; ?>]" />
			<?php endfor; ?>
		</div>
		<div id="capitani-field">
			<?php foreach ($this->elencoCap as $key => $val): ?>
				<input<?php if(isset($this->cap->$val) && !empty($this->cap->$val)){ echo ' value="' . $this->cap->$val . '" title="' . $this->giocatoriId[$this->cap->$val]->ruolo . $this->giocatoriId[$this->cap->$val]->ruolo . '-' . $this->giocatoriId[$this->cap->$val]->cognome . ' ' . $this->giocatoriId[$this->cap->$val]->nome;if(file_exists(IMGDIR . 'foto/' . $this->cap->$val . '.jpg')) echo '-1"'; else echo '"';} ?> id="<?php echo $val; ?>" type="hidden" name="cap[<?php echo $val; ?>]" />
			<?php endforeach; ?>
		</div>
	</fieldset>
</form>
<?php else: ?>
<span>Formazione non impostata</span>
<?php endif; ?>
<script type="text/javascript">
// <![CDATA[
	<?php if(!empty($this->modulo)): ?>
	var modulo = Array();
	modulo['PP'] = <?php echo $this->modulo[0]; ?>;
	modulo['DD'] = <?php echo $this->modulo[1]; ?>;
	modulo['CC'] = <?php echo $this->modulo[2]; ?>;
	modulo['AA'] = <?php echo $this->modulo[3]; ?>;
	<?php endif; ?>
	var edit = false;
	var imgsUrl = '<?php echo IMGSURL; ?>';
// ]]>
</script>
