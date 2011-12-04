<form action="<?php echo Links::getLink('download'); ?>" method="post">
	<fieldset class="no-margin no-padding">
		<select<?php echo (!isset($this->filesVoti)) ? ' disabled="disabled"' : ''; ?> id="giornataSelect" name="giornata">
			<option></option>
			<option value="all">Tutte le giornate</option>
			<?php if(isset($this->filesVoti)): ?>
				<?php foreach ($this->filesVoti as $key=>$val): ?>
					<option<?php echo (isset($_POST['giornata']) && $_POST['giornata'] == $val) ? ' selected="selected"' : ''; ?> value="<?php echo $val; ?>"><?php echo $val; ?></option>
				<?php endforeach; ?>
			<?php endif; ?>
		</select>
		<input class="radio" type="radio" name="type" value="csv"<?php echo (isset($_POST['type']) && $_POST['type'] == 'csv') ? ' checked="checked"' : ''; ?> />CSV
		<input class="radio" type="radio" name="type" value="xml"<?php echo (isset($_POST['type']) && $_POST['type'] == 'xml') ? ' checked="checked"' : ''; ?> />XML
		<input class="submit dark" type="submit" name="submit" value="Download"/>
	</fieldset>
</form>
<script type="text/javascript">
	var url = '<?php echo AJAXURL; ?>';
</script>
