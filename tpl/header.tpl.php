<a class="column last" title="Home" href="<?php echo Links::getLink('home'); ?>">
	<h1>FantaManajer</h1>
</a>
<?php if(PARTITEINCORSO == FALSE): ?>
	<div id="countdown" class="column last">Tempo rimanente per la formazione<br /><div>&nbsp;</div></div>
	<script type="text/javascript">
		// <![CDATA[
		var d = new Date();
		d.setFullYear(<?php echo $this->dataFine['year'] . ',' . ($this->dataFine['month'] - 1) . ',' . $this->dataFine['day']; ?>);
		d.setHours(<?php echo $this->dataFine['hour'] . ',' . $this->dataFine['minute'] . ',' . $this->dataFine['second']; ?>);
		// ]]>
	</script>
<?php endif; ?>
