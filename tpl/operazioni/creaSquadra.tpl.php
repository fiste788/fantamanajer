<?php if($this->elencoSquadre != FALSE): ?>
	<div class="elencoSquadre last">
		<?php if($this->action != 'new'): ?>
			<h4>
				<a href="<?php echo $this->linksObj->getLink('creaSquadra',array('a'=>'new','id'=>'0','lega'=>$this->lega)); ?>">Crea una squadra</a>
			</h4>
		<?php endif; ?>
		<h3>Elenco squadre</h3>
		<ul class="column">
		<?php foreach($this->elencoSquadre as $key => $val): ?>
			<li>
				<p class="column last"><?php echo $val['nome']; ?></p>
				<a class="right last" href="<?php echo $this->linksObj->getLink('creaSquadra',array('a'=>'cancel','id'=>$val['idUtente'],'lega'=>$this->lega)); ?>">
					<img src="<?php echo IMGSURL.'cancel.png'; ?>" alt="e" title="Cancella" />
				</a>
				<a class="right last" href="<?php echo $this->linksObj->getLink('creaSquadra',array('a'=>'edit','id'=>$val['idUtente'],'lega'=>$this->lega)); ?>">
					<img src="<?php echo IMGSURL.'edit.png'; ?>" alt="m" title="Modifica" />
				</a>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
<?php if($_SESSION['roles'] == '2'): ?>
<form class="column last" name="selezionaLega" action="<?php echo $this->linksObj->getLink('creaSquadra'); ?>" method="post">
	<input type="hidden" name="p" value="creaSquadra" />
	<input type="hidden" name="a" value="new" />
	<input type="hidden" name="id" value="0" />
	<fieldset class="no-margin fieldset max-large">
		<h3>Seleziona la lega</h3>
		<select name="lega" onchange="document.selezionaLega.submit();">
			<?php if(!isset($this->lega)): ?><option></option><?php endif; ?>
			<?php foreach($this->elencoLeghe as $key => $val): ?>
				<option<?php if($this->lega == $val['idLega']) echo ' selected="selected"'; ?> value="<?php echo $val['idLega']; ?>"><?php echo $val['nomeLega']; ?></option> 
			<?php endforeach; ?>
		</select>
	</fieldset>
</form>
<?php endif; ?>