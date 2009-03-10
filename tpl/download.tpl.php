<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'freeplayer-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Area Download</h2>
</div>
<div id="download_voti" class="main-content">
<form name="giornata_form" action="<?php echo $this->linksObj->getLink('download'); ?>" method="post">
<select name="file" onchange="document.giornata_form.submit();">>
<?php foreach ($this->filesvoti as $key=>$val): ?>
	<option <?php if($this->filesel == $val) echo "selected=\"selected\"" ?> value="<?php echo $val;?>"><?php echo "Giornata ".($key+1); ?></option>
					<?php endforeach; ?>
</select>

</form>
<form name="download_form" action="<?php echo $this->downloadpath;?>" method="post">
<input class="submit" type="submit" value="Download"/>
</form>
</div>
<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
	<?php if(isset($_SESSION['message']) && $_SESSION['message'][0] == 0): ?>
		<div id="messaggio" class="messaggio good column last">
			<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
			<span><?php echo $_SESSION['message'][1]; ?></span>
		</div>
		<script type="text/javascript">
		window.onload = (function(){
 			$("#messaggio").effect("pulsate", { times: 3 }, 1000);
			$("#messaggio").click(function () {
				$("div#messaggio").fadeOut("slow");
			});
 		});
		</script>
		<?php unset($_SESSION['message']); ?>
	<?php endif; ?>
		<?php if($_SESSION['logged'] == TRUE): ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		<?php endif; ?>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>