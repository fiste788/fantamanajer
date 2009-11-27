<?php if(isset($this->penalità)): ?>
	<div id="messaggio" class="messaggio neut column last">
		<img alt="!" src="<?php echo IMGSURL.'attention-big.png'; ?>" title="Attenzione!" />
		<span>Penalità: <?php echo $this->penalità['punteggio']; ?> punti<br />Motivazione: <?php echo $this->penalità['penalità']; ?></span><br />
	</div>
	<?php endif; ?>
	<form class="column last" action="<?php echo $this->linksObj->getLink('dettaglioGiornata'); ?>" method="post">
		<fieldset class="no-margin fieldset max-large">
			<input type="hidden" name="p" value="<?php echo $_GET['p'];?>" />
			<h3 class="no-margin">Seleziona la giornata</h3>
			<select name="giorn" onchange="this.form.submit();">
				<?php if(!isset($this->idGiornata)): ?><option></option><?php endif; ?>
				<?php for($i = $this->giornate ; $i > 0 ; $i--): ?>
					<option<?php if($this->idGiornata == $i) echo ' selected="selected"'; ?> value="<?php echo $i?>"><?php echo $i?></option>
				<?php endfor; ?>
			</select>
			<h3 class="no-margin">Seleziona la squadra</h3>
			<select name="squad" onchange="this.form.submit();">
				<?php if(!isset($this->idSquadra)): ?><option></option><?php endif; ?>
				<?php foreach($this->squadre as $key => $val): ?>
					<option<?php if($this->idSquadra == $val['idUtente']) echo ' selected="selected"'; ?> value="<?php echo $val['idUtente']?>"><?php echo $val['nome']?></option>
				<?php endforeach; ?>
			</select>
		</fieldset>
	</form>
