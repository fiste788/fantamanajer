<form action="<?php echo $this->linksObj->getLink('download'); ?>" method="post">
	<fieldset class="no-margin no-padding">
		<select id="giornata" name="giornata">
			<option></option>
			<option value="all">Tutte le giornate</option>
		<?php foreach ($this->filesVoti as $key=>$val): ?>
			<option<?php if(isset($_POST['giornata']) && $_POST['giornata'] == $val) echo ' selected="selected"'; ?> value="<?php echo $val; ?>"><?php echo $val; ?></option>
		<?php endforeach; ?>
		</select>
		<input class="submit dark" type="submit" value="Download"/>
	</fieldset>
</form>
