<div class="box-content column last">
<?php if(isset($_SESSION['message']) && $_SESSION['message'][0] == 0): ?>
	<div id="messaggio" class="messaggio good column last">
		<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
		<span><?php echo $_SESSION['message'][1]; ?></span>
	</div>
	<script type="text/javascript">
	window.onload = (function(){
			$("#messaggio").effect("pulsate", { times: 3 }, 1000);
		$("#messaggio").click(function () {
			$("div#messaggio").fadeOut("slow");
		});
		});
	</script>
	<?php unset($_SESSION['message']); ?>
<?php endif; ?>
	<?php if($_SESSION['logged'] == TRUE): ?>
		<?php require (TPLDIR.'operazioni.tpl.php'); ?>
	<?php endif; ?>
	<div id="operazioni-other" class="column last">
		<ul class="operazioni-content">
			<?php if(!$this->giornprec): ?>
				<li class="simil-link undo-punteggi-unactive column last">Indietro di una giornata</li>
			<?php else: ?>
				<li class="column last"><a class="undo-punteggi-active column last operazione" href="<?php echo $this->linksObj->getLink('conferenzeStampa',array('giorn'=>$this->giornprec)); ?>">Indietro di una giornata</a></li>
			<?php endif; ?>
			<?php if(!$this->giornsucc): ?>
				<li class="simil-link redo-punteggi-unactive column last">Avanti di una giornata</li>
			<?php else: ?>
			<li class="column last"><a class="redo-punteggi-active column last operazione" href="<?php echo $this->linksObj->getLink('conferenzeStampa',array('giorn'=>$this->giornsucc)); ?>">Avanti di una giornata</a></li>
			<?php endif; ?>
		</ul>
	</div>
	<form class="column last" name="idgiornata" action="<?php echo $this->linksObj->getLink('conferenzeStampa'); ?>" method="post">
		<fieldset class="no-margin fieldset">
			<input type="hidden" value="confStampa" name="p" />
			<h3 class="no-margin">Seleziona la giornata:</h3>
			<select name="giorn" onchange="document.idgiornata.submit();">
				<?php if($this->giornateWithArticoli != FALSE): ?>
				<?php foreach ($this->giornateWithArticoli as $key => $val): ?>
					<option <?php if($val == $this->getGiornata) echo "selected=\"selected\""; ?>><?php echo $val; ?></option>
				<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</fieldset>
	</form>
</div>
