<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'other-big.png'; ?>" alt="Logo Squadre" />
	</div>
	<h2 class="column">Esegui script</h2>
</div>
<div id="creaSquadre" class="main-content">
	<ul>
		<li><a class="script" id="backup" href="#">Backup</a></li>
		<li><a class="script" id="acquistaGioc" href="#">Trasferimenti</a></li>
	</ul>
	<script type="text/javascript">
		var loadingImg = "<?php echo IMGSURL.'lightbox-ico-loading.gif' ?>";
		$(".script").click(function () { 
			var Url = 'index.php?p=' + this.id;
			$.ajax({
				url: Url,
				type: "post",
				beforeSend: function(){
					$("#results").append('<img src="' + loadingImg + '"');
				},
				cache: false,
				success: function(){
					$("#results").empty();
					$("#results").append("Script eseguito con successo");
				},
				error:  function(){
					$("#results").empty();
					$("#results").append("Errore nell'esecuzione dello script'");
				}
			});
		});
	</script>
	<div id="results">&nbsp;</div>
</div>
