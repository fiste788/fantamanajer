<a class="linkheader column last" title="Home" href="<?php echo $this->linksObj->getLink('home'); ?>">
	<img alt="Header-logo" src="<?php echo IMGSURL.'header-logo.png'; ?>" />
</a>
<?php $appo = array_splice($_GET,1,count($_GET)); ?>
<?php if(count($this->leghe) > 1): ?>
<form class="column" name="legheView" action="<?php echo $this->linksObj->getLink($this->p,$appo); ?>" method="post">
<select onchange="document.legheView.submit();" name="legaView">
	<?php foreach($this->leghe as $key=>$value): ?>
	<option <?php if($_SESSION['legaView'] == $value['idLega']) echo ' selected="selected"' ?> value="<?php echo $value['idLega']; ?>"><?php echo $value['nomeLega']; ?></option>
	<?php endforeach; ?>
</select>
</form>
<?php endif; ?>