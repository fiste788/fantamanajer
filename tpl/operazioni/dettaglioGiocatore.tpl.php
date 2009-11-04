<div id="operazioni-other" class="column last">
	<h3 align="center" class="no-margin"><?php echo $this->label; ?></h3>
	<ul class="operazioni-content">
	<?php 
		$linkParams=array('edit'=>'view','id'=>$this->idGioc);
		if(!$this->giocPrec): ?>
			<li class="simil-link undo-punteggi-unactive column last">Giocatore precedente</li>
		<?php else: ?>
			<li class="column last"><a title="<?php echo $this->elencoGiocatori[$this->giocPrec]['cognome'] . ' ' . $this->elencoGiocatori[$this->giocPrec]['nome']; ?>" class="undo-punteggi-active column last operazione" href="<?php $linkParams['id'] = $this->giocPrec; echo $this->linksObj->getLink('dettaglioGiocatore',$linkParams); ?>">Giocatore precedente</a></li>
		<?php endif; ?>
		<?php if(!$this->giocSucc): ?>
			<li class="simil-link redo-punteggi-unactive column last">Giocatore successivo</li>
		<?php else: ?>
			<li class="column last"><a title="<?php echo $this->elencoGiocatori[$this->giocSucc]['cognome'] . ' ' . $this->elencoGiocatori[$this->giocSucc]['nome'];?>" class="redo-punteggi-active column last operazione" href="<?php $linkParams['id'] = $this->giocSucc ; echo $this->linksObj->getLink('dettaglioGiocatore',$linkParams); ?>">Giocatore successivo</a></li>
		<?php endif; ?>
	</ul>
</div>
<form class="column last" name="gioc" action="<?php echo $this->linksObj->getLink('dettaglioGiocatore'); ?>" method="post">
	<fieldset class="no-margin fieldset">
		<input type="hidden" value="<?php echo $_GET['p'];?>" />
		<input type="hidden" value="<?php echo $_GET['edit'];?>" name="edit" />
		<h3 class="no-margin">Seleziona il giocatore:</h3>
		<select name="id" onchange="document.gioc.submit();">
			<?php if($this->elencoGiocatori != FALSE): ?>
			<?php foreach ($this->elencoGiocatori as $key => $val): ?>
				<option <?php if($key == $this->idGioc) echo "selected=\"selected\""; ?> value="<?php echo $key;?>"><?php echo $val['cognome']." ".$val['nome']; ?></option>
			<?php endforeach; ?>
			<?php endif; ?>
		</select>
	</fieldset>
</form>