<form class="right last" action="<?php echo $this->linksObj->getLink('newsletter'); ?>" method="post">
	<fieldset class="no-margin fieldset">
		<h3 class="no-margin">Seleziona la lega</h3>
			<select name="lega" onchange="this.form.submit();">
			<?php if(!isset($this->lega)): ?><option></option><?php endif; ?>
				<option<?php if(isset($this->lega) && $this->lega == 0) echo ' selected="selected"'; ?> value="0">Tutte le leghe</option>
			<?php foreach($this->elencoLeghe as $key => $val): ?>
				<option<?php if($this->lega == $val['idLega']) echo ' selected="selected"'; ?> value="<?php echo $val['idLega']; ?>"><?php echo $val['nomeLega']; ?></option>
			<?php endforeach;?>
		</select>
	</fieldset>
</form>
