<?php ?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<body style="font-family:'Trebuchet MS',Arial,sans-serif;overflow:hidden">
		<a title="Home" href="<?php echo PROTO . $_SERVER['SERVER_NAME']; ?>">
		<img style="border:0 none" alt="Header-logo" src="<?php echo IMGSURL . 'header.png'; ?>" />
		</a>
		<div style="clear:both;width:100%;" >
			<h3 style="color:#00a2ff;text-decoration:none;">Penalità!</h3>
			<p style="width:100%margin-bottom:15px;">Sei stato penalizzato di <?php echo $this->punti; ?> punti nella giornata <?php echo $this->giornata; ?> con la motivazione: '<?php echo $this->motivo; ?>'</p>
			<p style="font-size:12px;clear:both;float:left;width:100%;">
			Si prega di non rispondere a questa mail in quanto non verrà presa in considerazione.<br />
			Per domande o chiarimenti contatta <?php if($this->autore->amministratore != '2'): ?>l'amministratore di lega all'indirizzo <a style="color:#00a2ff;text-decoration:none;" href="mailto:<?php echo $this->autore->mail; ?>"><?php echo $this->autore->mail; ?></a> o <?php endif; ?>gli amministratori all'indirizzo <a style="color:#00a2ff;text-decoration:none;" href="mailto:admin@fantamanajer.it">admin@fantamanajer.it</a>
		</div>
	</body>
</html>
