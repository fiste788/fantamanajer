<div id="modificaGioc" class="main-content"> 
	<form enctype="multipart/form-data" id="formModifica" name="modifica" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<div id="dettaglioGioc"><?php if(!isset($_POST['idGioc'])): ?>Seleziona un giocatore<?php endif; ?></div>
	</form>
</div>