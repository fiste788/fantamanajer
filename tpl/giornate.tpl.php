<form action="<?php echo $this->linksObj->getLink('giornate'); ?>" method="post">
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
				<td><input type="text" name="dataInizio[<?php echo $val->idGiornata; ?>]" value="<?php if(isset($_POST['dataInizio'][$val->idGiornata])) echo $_POST['dataInizio'][$val->idGiornata]; else echo $val->dataInizio; ?>" /></td>
				<td><input type="text" name="dataFine[<?php echo $val->idGiornata; ?>]" value="<?php if(isset($_POST['dataFine'][$val->idGiornata])) echo $_POST['dataFine'][$val->idGiornata]; else echo $val->dataFine; ?>" /></td>
			</tr>
		<?php endforeach; ?>
		</table>
		<input type="submit" name="submit" value="Invia" class="submit" />
	</fieldset>
</form>
