<div class="span4 hidden-phone">
    <a title="Home" href="<?php echo $this->router->generate('home'); ?>">
        <h1>FantaManajer</h1>
    </a>
</div>
<?php if(!$this->stagioneFinita): ?>
	<div class="hidden-phone" id="countdown" data-data-fine="<?php echo $this->timestamp ?>">Tempo rimanente per la formazione<br />
		<div><?php echo $this->dataFine['year'] . '-' . ($this->dataFine['month'] - 1) . '-' . $this->dataFine['day'] . ' ' . $this->dataFine['hour'] . ':' . $this->dataFine['minute'] . ':' . $this->dataFine['second']; ?></div>
	</div>
<?php endif; ?>
