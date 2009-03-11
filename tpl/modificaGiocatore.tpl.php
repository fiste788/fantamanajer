<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'freeplayer-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Modifica Giocatore</h2>
</div>
<div id="modificaGioc" class="main-content"> 
	<form enctype="multipart/form-data" id="formModifica" name="modifica" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<div id="dettaglioGioc"><?php if(!isset($_POST['idGioc'])): ?>Seleziona un giocatore<?php endif; ?></div>
	</form>
</div>
<?php if($_SESSION['logged'] == TRUE): ?>
	<div id="squadradett" class="column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
		<?php if(isset($this->message) && $this->message[0] == 0): ?>
		<div id="messaggio" class="messaggio good column last">
			<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
			<span><?php echo $this->message[1]; ?></span>
		</div>
		<?php elseif(isset($this->message) && $this->message[0] == 1): ?>
		<div id="messaggio" class="messaggio bad column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-bad-big.png'; ?>" />
			<span><?php echo $this->message[1]; ?></span>
		</div>
		<?php endif; ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
			<form class="column last" id="ricerca" action="<?php echo $this->linksObj->getLink('modificaGiocatore'); ?>" method="post">
				<fieldset class="no-margin fieldset">
					<h3 class="no-margin">Seleziona il giocatore</h3>
					<select name="ricercaGioc" id="ricercaGioc">
						<option></option>
						<?php foreach($this->giocatori as $key=>$val): ?>
						<option value="<?php echo $val['idGioc'] ?>" <?php if($_POST['idGioc'] == $val['idGioc']) echo 'selected="selected"' ?>><?php echo $val['cognome'] . " " . $val['nome']; ?></option>
						<?php endforeach; ?>
					</select>
				</fileset>
			</form>
			<script type="text/javascript">
				<!--
				$("#ricerca select").change(function () {
					if(this.value != "")
					{
						$.ajax({
							url: 'dettaglioGiocatore/edit/' + this.value + '.html',
							type: "post",
							cache: false,
							dataType: "xml",
							complete: function(xml,text){
								dettaglio = $("#dettaglioGioc",xml.responseText);
								$("#dettaglioGioc").empty();
								$("#dettaglioGioc").html($(dettaglio).html());
								$("#upload").after('<input type="button" name="button" class="submit dark" value="Modifica" onclick="document.forms[0].submit()" />');
							}
						});
					}
				});
				<?php if(isset($_POST['idGioc'])): ?>
					$.ajax({
						url: 'dettaglioGiocatore/edit/<?php echo $_POST['idGioc'] ?>.html',
						type: "post",
						cache: false,
						dataType: "xml",
						complete: function(xml,text){
							dettaglio = $("#dettaglioGioc",xml.responseText);
							$("#dettaglioGioc").empty();
							$("#dettaglioGioc").html($(dettaglio).html());
							$("#upload").after('<input type="button" name="button" class="submit dark" value="Modifica" onclick="document.forms[0].submit()" />');
						}
					});
				<?php endif; ?>
				-->
			</script>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
<?php else: ?>
	<div class="right">&nbsp;</div>
<?php endif; ?>
