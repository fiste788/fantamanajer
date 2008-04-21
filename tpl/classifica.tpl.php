<?php $i=1; ?>
<div class="titolo-pagina">
<div class="column logo-tit">
	<img alt="->" src="<?php echo IMGSURL. 'classifica-big.png'; ?>" />
</div>
<h2 class="column">Classifica</h2>
</div>
<div id="classifica-content" class="main-content">
	<table cellpadding="0" cellspacing="0" class="column last" style="width:290px;">
		<tbody>
			<tr>
				<th style="width:20px">P.</th>
				<th style="width:180px">Nome</th>
				<th style="width:70px">Punti tot</th>
			</tr>
			<?php foreach($this->classificaDett as $key=>$val): ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $this->squadre[$key-1][1]; ?></td>
				<td><?php echo array_sum($val); ?></td>
			 </tr>
			<?php $i++; endforeach; ?>
		</tbody>
	</table>
	<div id="tab_classifica" class="column last">
	<?php $i = 1; ?>
	<table class="column last" cellpadding="0" cellspacing="0" style="width:<?php echo count($this->classificaDett[$i])*50; ?>px;margin:0;">
		<tbody>
			<tr>
				<?php foreach($this->classificaDett[$i] as $key=>$val): ?>
					<th style="width:35px"><?php echo $key ?></th>
				<?php endforeach; ?>
			</tr>
			<?php foreach($this->classificaDett as $key=>$val): ?>
			<tr>
			<?php foreach($val as $secondKey=>$secondVal): ?>
				<td>
					<a href="index.php?p=punteggidettaglio&amp;giorn=<?php echo $secondKey; ?>&amp;squad=<?php echo $this->squadre[$key-1][0]; ?>"><?php echo $val[$secondKey]; ?></a>
				</td>
				<?php endforeach; ?>
			</tr>
			<?php $i++; endforeach; ?>
		</tbody>
	</table>
	</div>
</div>
<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
		<?php if($_SESSION['logged'] == TRUE): ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		<?php endif; ?>
		<form class="column last" name="classifica_giornata" action="index.php?p=classifica" method="post">
			<fieldset class="no-margin fieldset  max-large">
				<h3 class="no-margin">Guarda la classifica alla giornata</h3>
					<select name="giorn" onchange="document.classifica_giornata.submit();">
						<?php for($j = $this->giornate ; $j  > 0 ; $j--): ?>
							<option <?php if($this->getGiornata == $j) echo "selected=\"selected\"" ?>><?php echo $j; ?></option>
						<?php endfor; ?>
				</select>
			</fieldset>
		</form>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>

