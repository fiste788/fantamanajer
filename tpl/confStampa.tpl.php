<?php $i=0; ?>
<script type="text/javascript">
	$(document).ready(function(){
	  if(jQuery.browser.msie)
			$('.text').pngFix();
	});
</script>
<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'conf-stampa-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Conferenze stampa</h2>
</div>
<div id="confStampa" class="main-content">
	<?php if(isset($this->articoli) && !empty($this->articoli)):?>
		<?php foreach($this->articoli as $key=>$val): ?>
			<?php if($i%2 == 0): ?>
				<div class="riga column last">
			<?php endif; ?>
			<?php $i++; ?>		
			<div class="conf-stampa column<?php if($i%2 == 0) echo ' last'; ?>">
				<div class="box2-top-sx column last">
				<div class="box2-top-dx column last">
				<div class="box2-bottom-sx column last">
				<div class="box2-bottom-dx column last">
				<div class="conf-stampa-content column last">
					<?php if(isset($_SESSION['idsquadra']) && $_SESSION['idsquadra'] == $val['idSquadra']): ?>
						<a class="column last" href="index.php?p=editArticolo&amp;a=edit&amp;id=<?php echo $val['idArticolo']; ?>">
							<img src="<?php echo IMGSURL.'edit.png'; ?>" alt="m" title="Modifica" />
						</a>
						<a class="column" href="index.php?p=editArticolo&amp;a=cancel&amp;id=<?php echo $val['idArticolo']; ?>">
							<img src="<?php echo IMGSURL.'cancel.png'; ?>" alt="e" title="Cancella" />
						</a>
					<?php endif; ?>
					<em>
						<span class="column last"><?php echo $this->squadre[$val['idSquadra']-1][5]; ?></span>
						<span class="right"><?php echo $val['insertDate']; ?></span>
					</em>
					<h3 class="title"><?php echo $val['title']; ?></h3>
					<?php if(isset($val['abstract'])): ?><div class="abstract"><?php echo $val['abstract']; ?></div><?php endif; ?>
					<div class="text"><?php echo nl2br($val['text']); ?></div>
				</div>
				</div>
				</div>
				</div>
				</div>
			</div>
			<?php if($i%2 == 0 || $i == count($this->articoli)): ?>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
		<div>&nbsp;</div>
	<?php else: ?>
		Non sono presenti articoli
	<?php endif; ?>
</div>
<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
	<?php if(isset($_SESSION['message']) && $_SESSION['message'][0] == 0): ?>
		<div class="messaggio good column last">
			<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
			<span><?php echo $_SESSION['message'][1]; ?></span>
		</div>
		<script type="text/javascript">
		$(document).ready(function() {$('.messaggio').show('pulsate',{times: 3 }); });
		$(".messaggio").click(function () {
			$("div.messaggio").fadeOut("slow");
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
					<li class="column last"><a class="undo-punteggi-active column last operazione" href="index.php?p=confStampa&amp;giorn=<?php echo $this->giornprec; ?>">Indietro di una giornata</a></li>
				<?php endif; ?>
				<?php if(!$this->giornsucc): ?>
					<li class="simil-link redo-punteggi-unactive column last">Avanti di una giornata</li>
				<?php else: ?>
				<li class="column last"><a class="redo-punteggi-active column last operazione" href="index.php?p=confStampa&amp;giorn=<?php echo $this->giornsucc; ?>">Avanti di una giornata</a></li>
				<?php endif; ?>
			</ul>
		</div>
		<form class="column last" name="idgiornata" action="index.php?p=confStampa" method="get">
			<fieldset class="no-margin fieldset">
				<input type="hidden" value="confStampa" name="p" />
				<h3 class="no-margin">Seleziona la giornata:</h3>
				<select name="giorn" onchange="document.idgiornata.submit();">
					<option></option>
					<?php if($this->giornateWithArticoli != FALSE): ?>
					<?php foreach ($this->giornateWithArticoli as $key=>$val): ?>
						<option <?php if($val == $this->getGiornata) echo "selected=\"selected\""; ?>><?php echo $val; ?></option>
					<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</fieldset>
		</form>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
