<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'freeplayer-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Modifica Giocatore</h2>
</div>
<div id="modificaGioc" class="main-content"> 
	<table id="ricerca">
		<thead>
			<tr>
				<th>Cognome</th>
				<th>Nome</th>
				<th>Ruolo</th>
			</tr>
		</thead>
		<tbody>
			<?php $i = 0; ?>
			<?php foreach($this->giocatori as $key=>$val): ?>
			<tr>
				<td><a href="#" rel="<?php echo $val['idGioc'] ?>"><?php echo $val['cognome']; ?></a></td>
				<td><a href="#" rel="<?php echo $val['idGioc'] ?>"><?php echo $val['nome']; ?></a></td>
				<td><a href="#" rel="<?php echo $val['idGioc'] ?>"><?php echo $val['ruolo']; ?></a></td>
			</tr>
			<?php $i++; endforeach; ?>
		</tbody>
	</table>
	<div id="dettaglioGioc">&nbsp;</div>
	<script type="text/javascript">
		$("#ricerca a").click(function () {
			$.ajax({
				url: 'dettaglioGiocatore/' + this.rel + '.html',
				type: "post",
				cache: false,
				dataType: "xml",
				complete: function(xml,text){
					dettaglio = $("#dettaglioGioc",xml.responseText);
					$("#dettaglioGioc").empty();
					$("#dettaglioGioc").html($(dettaglio).html());
				}
			});
		});
		$(document).ready(function () {
			$('table#ricerca tbody tr').quicksearch({
				position: 'before',
				attached: '#ricerca',
				stripeRowClass: ['riga1', 'riga2'],
				labelText: 'Cerca nella tabella'
			});
		});
	</script>
</div>
<?php if($_SESSION['logged'] == TRUE): ?>
	<div id="squadradett" class="column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
<?php else: ?>
	<div class="right">&nbsp;</div>
<?php endif; ?>
