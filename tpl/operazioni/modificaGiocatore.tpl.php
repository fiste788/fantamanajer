<form class="form-inline" action="<?php echo Links::getLink('modificaGiocatore'); ?>" method="post">
	<fieldset>
		<label for="giocatore">Seleziona il giocatore</h3>
		<select name="giocatore">
			<option></option>
			<?php foreach($this->giocatori as $key=>$val): ?>
			<option value="<?php echo $val->idGioc; ?>"<?php echo (isset($_POST['idGioc']) && $_POST['idGioc'] == $val->idGioc) ? ' selected="selected"' : ''; ?>><?php echo $val->cognome . " " . $val->nome; ?></option>
			<?php endforeach; ?>
		</select>
	</fieldset>
</form>
<script type="text/javascript">
// <![CDATA[
	<?php if(isset($_POST['idGioc'])): ?>
	$.ajax({
		url: 'dettaglioGiocatore/edit/<?php echo $_POST['idGioc'] ?>.html',
		type: "post",
		cache: false,
		dataType: "xml",
		complete: function(xml,text){
			dettaglio = $("#dettaglioGiocatore",xml.responseText);
			$("#dettaglioGiocatore").empty();
			$("#dettaglioGiocatore").html($(dettaglio).html());
			$("#upload").after('<input type="button" name="button" class="submit dark" value="Modifica" onclick="document.modifica.submit()" />');
		}
	});
	<?php endif; ?>
// ]]>
</script>
