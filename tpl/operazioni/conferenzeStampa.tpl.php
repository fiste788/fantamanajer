<div id="operazioni-other" class="column last">
	<ul class="operazioni-content">
		<?php if(!$this->giornPrec): ?>
			<li class="simil-link undo-punteggi-unactive column last">Indietro di una giornata</li>
		<?php else: ?>
			<li class="column last"><a class="undo-punteggi-active column last operazione" href="<?php echo $this->linksObj->getLink('conferenzeStampa',array('giornata'=>$this->giornPrec)); ?>">Indietro di una giornata</a></li>
		<?php endif; ?>
		<?php if(!$this->giornSucc): ?>
			<li class="simil-link redo-punteggi-unactive column last">Avanti di una giornata</li>
		<?php else: ?>
			<li class="column last"><a class="redo-punteggi-active column last operazione" href="<?php echo $this->linksObj->getLink('conferenzeStampa',array('giornata'=>$this->giornSucc)); ?>">Avanti di una giornata</a></li>
	<?php endif; ?>
	</ul>
</div>
<form class="column last" name="idgiornata" action="<?php echo $this->linksObj->getLink('conferenzeStampa'); ?>" method="post">
	<fieldset class="no-margin fieldset">
		<input type="hidden" value="confStampa" name="p" />
		<h3 class="no-margin">Seleziona la giornata:</h3>
		<select name="giornata" onchange="document.idgiornata.submit();">
		<?php if($this->giornateWithArticoli != FALSE): ?>
			<?php foreach ($this->giornateWithArticoli as $key => $val): ?>
				<option<?php if($val == $this->idGiornata) echo ' selected="selected"'; ?>><?php echo $val; ?></option>
			<?php endforeach; ?>
		<?php endif; ?>
		</select>
	</fieldset>
</form>
