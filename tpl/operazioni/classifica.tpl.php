<div class="box-content column last">
	<?php if($_SESSION['logged'] == TRUE): ?>
		<?php require (TPLDIR.'operazioni.tpl.php'); ?>
	<?php endif; ?>
	<form class="column last" name="classifica_giornata" action="<?php echo $this->linksObj->getLink('classifica'); ?>" method="post">
		<fieldset class="no-margin fieldset max-large">
			<h3 class="no-margin">Guarda la classifica alla giornata</h3>
				<select name="giorn" onchange="document.classifica_giornata.submit();">
					<?php for($j = $this->giornate ; $j  > 0 ; $j--): ?>
						<option <?php if($this->getGiornata == $j) echo "selected=\"selected\"" ?>><?php echo $j; ?></option>
					<?php endfor; ?>
			</select>
		</fieldset>
	</form>
</div>
