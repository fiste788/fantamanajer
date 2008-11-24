<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'other-big.png'; ?>" alt="Logo Squadre" />
	</div>
	<h2 class="column">Gestione database</h2>
</div>
<div id="gestioneDb" class="main-content">
	<ul>
		<li><a href="<?php echo $this->linksObj->getLink('gestioneDatabase',array('action'=>'optimize')) ?>">Ottimizza</a></li>
		<li><a href="<?php echo $this->linksObj->getLink('gestioneDatabase',array('action'=>'sincronize')) ?>">Sincronizza</a></li>
	</ul>
	<form action="<?php echo $this->linksObj->getLink('gestioneDatabase'); ?>" name="eseguiQuery" method="post">
		<p class="no-margin">Inserisci quì la tua query</p>
		<textarea name="query" rows="30" cols="100"><?php if(isset($this->sql)) echo $this->sql; ?></textarea>
		<input class="submit dark" type="submit" value="Eegui" />
	</form>
</div>                                                    
<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">        
		<?php if(isset($this->messaggio) && $this->messaggio[0] == 0): ?>
		<div class="messaggio good column last">
			<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
			<span><?php echo $this->messaggio[1]; ?></span>
		</div>
		<?php elseif(isset($this->messaggio) && $this->messaggio[0] == 1): ?>
		<div class="messaggio bad column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-bad-big.png'; ?>" />
			<span><?php echo $this->messaggio[1]; ?></span>
		</div>
		<?php endif; ?>
		<?php if(isset($this->messaggio)): ?>
		<script type="text/javascript">
		$(document).ready(function() {
			$('.messaggio').show('pulsate',{times: 3 }, function() {
				if(jQuery.browser.msie)
					$(".messaggio").removeAttr('style');
			}); 
		});
		$(".messaggio").click(function () {
			$("div.messaggio").fadeOut("slow");
		});
		</script>
		<?php endif; ?>
		<?php require (TPLDIR.'operazioni.tpl.php'); ?>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
