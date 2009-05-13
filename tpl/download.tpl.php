<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'download-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Area Download</h2>
</div>
<div id="download_voti" class="main-content">
	<form name="giornataForm" action="<?php echo $this->linksObj->getLink('download'); ?>" method="post">
		<select id="giornata" name="giornata" >
			<option></option>
			<option value="<?php echo TOZIP;?>">Tutte le giornate</option>
		<?php foreach ($this->filesVoti as $key=>$val): ?>
			<option<?php if(isset($_POST['giornata']) && $_POST['giornata'] == $val) echo ' selected="selected"' ?> value="<?php echo $val;?>"><?php echo $val; ?></option>
		<?php endforeach; ?>
		</select>
		<input class="submit dark" type="submit" value="Download"/>
	</form>
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
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
