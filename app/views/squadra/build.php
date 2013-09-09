<?php $j = 0; ?>
<form id="creaSq" class="column" action="<?php echo $this->router->generate('crea_squadra'); ?>" method="post">
    <fieldset>
        <div class="form-group">
            <label for="id" class="no-margin">Seleziona la squadra:</label>
            <select class="form-control" name="squadra">
                <?php foreach ($this->squadre as $val): ?>
                    <option value="<?php echo $val->id; ?>"><?php echo $val->nomeSquadra; ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </fieldset>
	<fieldset style="width:220px;" class="column">
		<h4 class="bold no-margin">Portieri</h4>
		<hr />
		<?php for($i = 0;$i < 3; $i++): ?>
			<select name="giocatore[]">
				<option></option>
				<?php foreach($this->portieri as $key => $val): ?>
					<option<?php echo (isset($_POST['giocatore'][$j]) && $_POST['giocatore'][$j] == $val->id) ? ' selected="selected"' : ''; ?> value="<?php echo $val->id; ?>"><?php echo $val->cognome . " " . $val->nome; ?></option>
				<?php endforeach; ?>
			</select>
		<?php $j++; endfor; ?>
		<h4 class="bold no-margin">Difensori</h4>
		<hr />
		<?php for($i = 0;$i < 8; $i++): ?>
			<select name="giocatore[]">
				<option></option>
				<?php foreach($this->difensori as $key => $val): ?>
					<option<?php echo (isset($_POST['giocatore'][$j]) && $_POST['giocatore'][$j] == $val->idGioc) ? ' selected="selected"' : ''; ?> value="<?php echo $val->id; ?>"><?php echo $val->cognome . " " . $val->nome; ?></option>
				<?php endforeach; ?>
			</select>
		<?php $j++; endfor; ?>
	</fieldset>
	<fieldset style="width:220px;" class="column last">
		<h4 class="bold no-margin">Centrocampisti</h4>
		<hr />
		<?php for($i = 0;$i < 8; $i++): ?>
			<select name="giocatore[]">
				<option></option>
				<?php foreach($this->centrocampisti as $key => $val): ?>
					<option<?php echo (isset($_POST['giocatore'][$j]) && $_POST['giocatore'][$j] == $val->idGioc) ? ' selected="selected"' : ''; ?> value="<?php echo $val->id; ?>"><?php echo $val->cognome . " " . $val->nome; ?></option>
				<?php endforeach; ?>
			</select>
		<?php $j++; endfor; ?>
		<h4 class="bold no-margin">Attaccanti</h4>
		<hr />
		<?php for($i = 0;$i < 6; $i++): ?>
			<select name="giocatore[]">
				<option></option>
				<?php foreach($this->attaccanti as $key => $val): ?>
					<option<?php echo (isset($_POST['giocatore'][$j]) && $_POST['giocatore'][$j] == $val->idGioc) ? ' selected="selected"' : ''; ?> value="<?php echo $val->id; ?>"><?php echo $val->cognome . " " . $val->nome; ?></option>
				<?php endforeach; ?>
			</select>
		<?php $j++; endfor; ?>
	</fieldset>
	<div id="dialog" title="Attenzione!" style="display:none;">
	<p>Sei sicuro di voler eliminare la squadra <br />"<?php echo $nomeSquadra; ?>"?</p>
	</div>
	<fieldset class="column no-margin div-submit">
			<input type="submit" name="button" class="submit dark" value="Ok" />
	</fieldset>
</form>
