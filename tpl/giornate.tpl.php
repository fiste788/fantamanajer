<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'conf-stampa-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Giornate</h2>
</div>
<div id="giornate" class="main-content">
	<form action="<?php echo $this->linksObj->getLink('giornate') ?>" method="post">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th>Id</th>
				<th>DataInizio</th>
				<th>DataFine</th>
			</tr>
		<?php $i = 1;foreach($this->giornate as $key=>$val): ?>
			<tr>
				<td><?php echo $val['idGiornata']; ?></td>
				<td><input type="text" name="dataInizio[<?php echo $val['idGiornata'] ?>]" value="<?php if(isset($_POST['dataInizio'][$val['idGiornata']])) echo $_POST['dataInizio'][$val['idGiornata']]; else echo $val['dataInizio']; ?>" /></td>
				<td><input type="text" name="dataFine[<?php echo $val['idGiornata'] ?>]" value="<?php if(isset($_POST['dataFine'][$val['idGiornata']])) echo $_POST['dataFine'][$val['idGiornata']]; else echo $val['dataFine']; ?>" /></td>
			</tr>
		<?php $i++;endforeach; ?>
		</table>
		<input type="submit" name="submit" value="Invia" class="submit dark" />
	</form>
</div>
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
	<?php if(isset($this->message)): ?> 
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
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
