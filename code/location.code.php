<?php 
if($_SESSION['logged'])
	header('Location: index.php?p=rosa&squadra='.$_SESSION['idsquadra']); 
?>
