<?php $j =0; $k = 0; $ruolo = ""; ?>
<?php  if(!PARTITEINCORSO): ?>
<h3>Giornata <?php echo GIORNATA; ?></h3>
<form action="<?php echo $this->linksObj->getLink('formazione'); ?>" method="post">
	<div id="campo" class="column">
		<div id="PP" class="droppable"></div>
		<div id="DD" class="droppable"></div>
		<div id="CC" class="droppable"></div>
		<div id="AA" class="droppable"></div>
	</div>
	<div id="giocatori" class="column">
	<?php foreach($this->giocatori as $key=>$val): ?>
		<?php if($ruolo != $val['ruolo']) echo '<div style="clear:both;line-height:1px">&nbsp;</div>'; ?>
		<div class="draggable giocatore <?php if((!empty($this->titolari) && in_array($val['idGioc'],$this->titolari)) || (!empty($this->panchinari) && in_array($val['idGioc'],$this->panchinari))) echo 'hidden'; ?> <?php echo $val['ruolo']; ?>">
			<a class="hidden" rel="<?php echo $val['idGioc']; ?>" name="<?php echo $val['ruolo'].$val['ruolo']; ?>"></a>
			<img alt="<?php echo $val['idGioc']; ?>" width="40" src="imgs/foto/<?php echo $val['idGioc']; ?>.jpg" />
			<p><?php echo $val['cognome'] . ' ' . $val['nome']; ?></p>
		</div>
		<?php if($ruolo != $val['ruolo']) $ruolo = $val['ruolo']; ?>
	<?php $j++; endforeach; ?>
	</div>
	<div id="panchina" class="column">
		<?php for($i = 0;$i < 7;$i++): ?>
			<div id="panch-<?php echo $i; ?>" class="droppable"></div>
		<?php endfor; ?>
	</div>
	<div id="capitani" class="column">
		<?php foreach ($this->elencoCap as $key => $val): ?>
			<div id="cap-<?php echo $val; ?>" class="droppable"></div>
		<?php endforeach; ?>
	</div>
	<div id="titolari-field">
		<?php for($i = 0;$i < 11;$i++): ?>
			<input<?php if(isset($this->titolari[$i])) echo ' value="' . $this->titolari[$i] . '" title="' . $this->giocatori[$this->titolari[$i]]['ruolo'] . $this->giocatori[$this->titolari[$i]]['ruolo'] . '-' . $this->giocatori[$this->titolari[$i]]['cognome'] . ' ' . $this->giocatori[$this->titolari[$i]]['nome'] . '"'; ?> id="gioc-<?php echo $i; ?>" type="hidden" name="gioc[<?php echo $i; ?>]" />
		<?php endfor; ?>
	</div>
	<div id="panchina-field">
		<?php for($i = 0;$i < 7;$i++): ?>
			<input<?php if(isset($this->panchinari[$i])) echo ' value="' . $this->panchinari[$i] . '" title="' . $this->giocatori[$this->panchinari[$i]]['ruolo'] . $this->giocatori[$this->panchinari[$i]]['ruolo'] . '-' . $this->giocatori[$this->panchinari[$i]]['cognome'] . ' ' . $this->giocatori[$this->panchinari[$i]]['nome'] . '"'; ?> id="panchField-<?php echo $i; ?>" type="hidden" name="panch[<?php echo $i; ?>]" />
		<?php endfor; ?>
	</div>
	<div id="capitani-field">
		<?php foreach ($this->elencoCap as $key => $val): ?>
			<input<?php if(isset($this->cap[$val])) echo 'value="' . $this->cap[$val] . '" title="' . $this->giocatori[$this->cap[$val]]['ruolo'] . $this->giocatori[$this->cap[$val]]['ruolo'] . '-' . $this->giocatori[$this->cap[$val]]['cognome'] . ' ' . $this->giocatori[$this->cap[$val]]['nome'] . '"'; ?> id="<?php echo $val; ?>" type="hidden" name="cap[<?php echo $val; ?>]" />
		<?php endforeach; ?>
	</div>
	<input name="button" type="submit" class="button" value="Invia" />
</form>
<?php endif; ?>
<?php if(!empty($this->modulo)): ?>
<script type="text/javascript">
	var modulo = Array();
	modulo['PP'] = <?php echo $this->modulo[0]; ?>;
	modulo['DD'] = <?php echo $this->modulo[1]; ?>;
	modulo['CC'] = <?php echo $this->modulo[2]; ?>;
	modulo['AA'] = <?php echo $this->modulo[3]; ?>;
</script>
<?php endif; ?>
<script type="text/javascript" src="<?php echo JSURL . 'custom/formazione.js'; ?>"></script>
