<?php if(isset($this->penalità)): ?>
	<div id="messaggio" class="messaggio neut column last">
		<img alt="!" src="<?php echo IMGSURL.'attention-big.png'; ?>" title="Attenzione!" />
		<span>Penalità: <?php echo $this->penalità['punteggio']; ?> punti<br />Motivazione: <?php echo $this->penalità['penalità']; ?></span><br />
	</div>
	<?php endif; ?>
	<div id="operazioni-other" class="column last">
		<ul class="operazioni-content">
			<?php if(!$this->giornprec): ?>
				<li class="simil-link undo-punteggi-unactive column last">Indietro di una giornata</li>
			<?php else: ?>
				<li class="column last"><a class="undo-punteggi-active column last operazione" href="<?php echo $this->linksObj->getLink('dettaglioGiornata',array('giorn'=>$this->giornprec,'squad'=>$this->getsquadra)); ?>">Indietro di una giornata</a></li>
			<?php endif; ?>
			<?php if(!$this->giornsucc): ?>
				<li class="simil-link redo-punteggi-unactive column last">Avanti di una giornata</li>
			<?php else: ?>
			<li class="column last"><a class="redo-punteggi-active column last operazione" href="<?php echo $this->linksObj->getLink('dettaglioGiornata',array('giorn'=>$this->giornsucc,'squad'=>$this->getsquadra)); ?>">Avanti di una giornata</a></li>
			<?php endif; ?>
		</ul>
	</div>
	<form class="column last" name="selsq" action="<?php echo $this->linksObj->getLink('dettaglioGiornata'); ?>" method="post">
		<input type="hidden" name="p" value="<?php echo $_GET['p'];?>" />
		<fieldset class="no-margin fieldset max-large">
			<h3 class="no-margin">Seleziona la giornata</h3>
			<select name="giorn" onchange="document.selsq.submit();">
				<?php if(!isset($this->getgiornata)): ?><option></option><?php endif; ?>
				<?php for($i = $this->giornate ; $i > 0 ; $i--): ?>
					<option<?php if($this->getgiornata == $i) echo ' selected="selected"'; ?> value="<?php echo $i?>"><?php echo $i?></option>
				<?php endfor; ?>
			</select>
			<h3 class="no-margin">Seleziona la squadra</h3>
			<select name="squad" onchange="document.selsq.submit();">
				<?php if(!isset($this->getsquadra)): ?><option></option><?php endif; ?>
				<?php foreach($this->squadre as $key => $val): ?>
					<option<?php if($this->getsquadra == $val['idUtente']) echo ' selected="selected"'; ?> value="<?php echo $val['idUtente']?>"><?php echo $val['nome']?></option>
				<?php endforeach; ?>
			</select>
		</fieldset>
	</form>
