<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'eventi-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Eventi</h2>
</div>
<div id="feed" class="main-content">
	<?php if(!empty($this->eventi)): ?>
	<?php foreach($this->eventi as $key =>$val): ?>
		<?php if($this->evento == 0 || $val['tipo'] == $this->evento): ?>
			<?php if($val['tipo'] != 2 && $_SESSION['logged']): ?>
				<a href="<?php echo $val['link']; ?>">
			<?php endif;?>
			<h3 name="evento-<?php echo $val['idEvento']; ?>"><?php echo $val['titolo']; ?>  <em>(<?php echo $val['data']; ?>)</em></h3>
			<?php if($val['tipo'] != 2 && $_SESSION['logged']): ?>
				</a>
			<?php endif;?>
			<?php if(isset($val['content'])): ?>
				<p><?php echo $val['content']; ?></p>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>
	<?php endif; ?>
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
		<form class="column last" name="eventi" action="<?php echo $this->linksObj->getLink('feed'); ?>" method="post">
		<fieldset class="no-margin fieldset max-large">
			<h3 class="no-margin">Seleziona il tipo di evento:</h3>
			<input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
			<select name="evento" onchange="document.eventi.submit();">
				<option></option>
				<option <?php if($this->evento == '1') echo "selected=\"selected\"" ?> value="1">Conferenze stampa</option>
				<option <?php if($this->evento == '2') echo "selected=\"selected\"" ?> value="2">Giocatore selezionato</option>
				<option <?php if($this->evento == '3') echo "selected=\"selected\"" ?> value="3">Formazione impostata</option>
				<option <?php if($this->evento == '4') echo "selected=\"selected\"" ?> value="4">Trasferimento</option>
			</select>
		</fieldset>
	</form>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
