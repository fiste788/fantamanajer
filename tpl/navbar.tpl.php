<ul>
	<?php foreach($this->entries as $key=>$val):
        if($key != ""):
		$selected = FALSE;
		if(in_array($this->request->get('p'),$val['pages'])) $selected = TRUE;
			if($selected): ?>
				<li class="selected">
			<?php else: ?>
				<li>
			<?php endif; ?>
			<div>
				<a href="<?php echo Links::getLink($key); ?>"><?php echo $val['title']; ?></a>
				<?php if($selected && !isset($this->pages[$this->request->get('p')]['navbar']['main'])): ?>
					<a class="son"> > </a>
					<a><?php echo $this->pages[$this->request->get('p')]['navbar']['title']; ?></a>
				<?php endif; ?>
			</div>
			</li>
	<?php endif; endforeach; ?>
</ul>
<div id="rightNavbar">
	<?php if(count($this->leghe) > 1): ?>
		<?php $appo = $_GET; unset($appo['p']); ?>
		<form class="column last" action="<?php echo Links::getLink($this->request->get('p'),$appo); ?>" method="post">
			<fieldset>
				<label class="lega" for="legaView">Lega:</label>
				<select id="legaView" onchange="this.form.submit();" name="legaView">
					<?php foreach($this->leghe as $key=>$value): ?>
						<option <?php echo ($_SESSION['legaView'] == $key) ? ' selected="selected"' : ''; ?> value="<?php echo $key; ?>"><?php echo $value->getNome(); ?></option>
					<?php endforeach; ?>
				</select>
			</fieldset>
		</form>
	<?php endif; ?>
	<?php if($_SESSION['logged']): ?>
		<div id="account">
            <a id="notifiche" class="entry">
                <span<?php if(!empty($this->notifiche)) echo ' class="active"'; ?> title="Clicca per vedere le notifiche"><?php echo count($this->notifiche); ?></span>
            </a>
            <?php if(!empty($this->notifiche)): ?>
                <div class="boxNotifiche">
                <?php foreach($this->notifiche as $key=>$val): ?>
                    <a href="<?php echo $val->link; ?>"><?php echo $val->text; ?></a>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
		</div>
	<?php endif; ?>
	<?php require_once(TPLDIR . "login.tpl.php") ?>
</div>
