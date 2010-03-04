<form action="<?php echo Links::getLink('giornate'); ?>" method="post">
	<fieldset class="no-margin no-padding">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th>Id</th>
				<th>DataInizio</th>
				<th>DataFine</th>
			</tr>
		<?php foreach($this->giornate as $key=>$val): ?>
			<tr>
				<td><?php echo $val->idGiornata; ?></td>
				<td><input class="end" type="text" name="dataInizio[<?php echo $val->idGiornata; ?>]" value="<?php echo (isset($_POST['dataInizio'][$val->idGiornata])) ? $_POST['dataInizio'][$val->idGiornata] : $val->dataInizio; ?>" /></td>
				<td><input class="start" type="text" name="dataFine[<?php echo $val->idGiornata; ?>]" value="<?php echo (isset($_POST['dataFine'][$val->idGiornata])) ? $_POST['dataFine'][$val->idGiornata] : $val->dataFine; ?>" /></td>
			</tr>
		<?php endforeach; ?>
		</table>
		<input type="submit" name="submit" value="Invia" class="submit" />
	</fieldset>
</form>
