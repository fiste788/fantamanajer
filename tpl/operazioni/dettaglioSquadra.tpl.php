<div id="operazioni-other" class="column last">
	<ul class="operazioni-content">
		<?php if(!$this->squadraPrec): ?>
			<li class="simil-link undo-punteggi-unactive column last">Squadra precedente</li>
		<?php else: ?>
			<li class="column last"><a title="<?php echo $this->elencoSquadre[$this->squadraPrec]['nome']; ?>" class="undo-punteggi-active column last operazione" href="<?php echo $this->linksObj->getLink('rosa',array('squadra'=>$this->squadraPrec)); ?>">Squadra precedente</a></li>
		<?php endif; ?>
		<?php if(!$this->squadraSucc): ?>
			<li class="simil-link redo-punteggi-unactive column last">Squadra successiva</li>
		<?php else: ?>
		<li class="column last"><a title="<?php echo $this->elencoSquadre[$this->squadraSucc]['nome']; ?>" class="redo-punteggi-active column last operazione" href="<?php echo $this->linksObj->getLink('rosa',array('squadra'=>$this->squadraSucc)); ?>">Squadra successiva</a></li>
		<?php endif; ?>
	</ul>
</div>