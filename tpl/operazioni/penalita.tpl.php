<form class="column last" action="<?php echo $this->linksObj->getLink('penalita'); ?>" method="post">
	<fieldset class="no-margin fieldset max-large">
		<?php if($_SESSION['roles'] == 2): ?>
			<h3 class="no-margin">Seleziona la lega:</h3>
			<select name="lega">
				<?php if($this->lega == NULL): ?><option></option><?php endif; ?>
				<?php foreach($this->elencoLeghe as $key => $val): ?>
					<option<?php if($this->lega == $val['idLega']) echo ' selected="selected"'; ?> value="<?php echo $val['idLega']; ?>"><?php echo $val['nomeLega']; ?></option>
				<?php endforeach ?>
			</select>
		<?php endif; ?>
		<h3 class="no-margin">Seleziona la giornata:</h3>
		<input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
		<select id="giorn" name="giornata">
			<?php if(!isset($this->giornata)): ?><option></option><?php endif; ?>
			<?php for($i = $this->giornate ; $i > 0 ; $i--): ?>
				<option<?php if($this->giornata == $i) echo ' selected="selected"'; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
			<?php endfor; ?>
		</select>
		<?php if(isset($this->lega) && $this->lega != NULL && isset($this->giornata) && $this->giornata != NULL): ?>
			<h3 class="no-margin">Seleziona la squadra:</h3>
			<?php if(!$this->elencoSquadre): ?>
				<select disabled="disabled" name="squadra">
					<option value="NULL">Nessuna squadra presente</option>
			<?php else: ?>
				<select name="squadra">
				<?php if($this->squadra == NULL): ?><option></option><?php endif; ?>
				<?php foreach($this->elencoSquadre as $key => $val): ?>
					<option<?php if($this->squadra == $val['idUtente']) echo ' selected="selected"'; ?> value="<?php echo $val['idUtente']; ?>"><?php echo $val['nome']; ?></option>
				<?php endforeach ?>
			<?php endif; ?>
			</select>
		<?php endif; ?>
		<input type="submit" class="submit" value="OK" />
	</fieldset>
</form>
