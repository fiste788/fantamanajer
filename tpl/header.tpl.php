<a class="column last" title="Home" href="<?php echo Links::getLink('home'); ?>">
	<img width="319" height="72" alt="FantaManajer" src="<?php echo IMGSURL . 'header.png'; ?>" />
</a>
<?php if(PARTITEINCORSO == FALSE): ?>
	<div id="countdown" class="column last">Tempo rimanente per la formazione<br /><div>&nbsp;</div></div>
	<script type="text/javascript">
		// <![CDATA[
		var d = new Date();
		d.setFullYear(<?php echo '2011,10,16' //$this->dataFine['year'] . ',' . ($this->dataFine['month'] - 1) . ',' . $this->dataFine['day']; ?>);
		d.setHours(<?php echo $this->dataFine['hour'] . ',' . $this->dataFine['minute'] . ',' . $this->dataFine['second']; ?>);
		// ]]>
	</script>
<?php endif; ?>