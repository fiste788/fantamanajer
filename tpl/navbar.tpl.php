<ul>
	<?php foreach($this->entries->navbar as $key=>$val):
		$selected = FALSE;
		if(in_array($this->request->get('p'),$this->entries->pages[$key]->pages)) $selected = TRUE;
			if($selected): ?>
				<li class="selected">
			<?php else: ?>
				<li>
			<?php endif; ?>
				<a class="level<?php if(!empty($val)) echo " dropdown-toggle"; ?>" href="<?php echo Links::getLink($key); ?>"><?php echo $this->entries->pages[$key]->title; ?></a>
				<?php if(!empty($val)): ?>
					<ul class="dropdown-menu subnav">
					<?php foreach($val as $key2=>$val2): ?>
						<li><a href="<?php echo Links::getLink($val2); ?>"><?php echo $this->entries->pages[$val2]->title; ?></a></li>
					<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</li>
	<?php endforeach; ?>


	<?php if(count($this->leghe) > 1): ?>
	<li id="rightNavbar" class="right">
		<?php $appo = $_GET; unset($appo['p']); ?>
		<form  action="<?php echo Links::getLink($this->request->get('p'),$appo); ?>" method="post">
			<fieldset>
				<label style="width:50px" for="legaView">Lega:</label>
				<select id="legaView" onchange="this.form.submit();" class="medium" name="legaView">
					<?php foreach($this->leghe as $key=>$value): ?>
						<option <?php echo ($_SESSION['legaView'] == $key) ? ' selected="selected"' : ''; ?> value="<?php echo $key; ?>"><?php echo $value->getNome(); ?></option>
					<?php endforeach; ?>
				</select>
			</fieldset>
		</form>
		</li>
	<?php endif; ?>
	<?php if($_SESSION['logged']): ?>
	<li id="account" class="right">
		
            <a id="notifiche" <?php if(!empty($this->notifiche)) echo ' class="dropdown-toggle nopick"'; ?>>
                <span<?php if(!empty($this->notifiche)) echo ' class="active"'; ?> title="Clicca per vedere le notifiche"><?php echo count($this->notifiche); ?></span>
            </a>
            <?php if(!empty($this->notifiche)): ?>
                <div class="boxNotifiche dropdown-menu">
                <?php foreach($this->notifiche as $key=>$val): ?>
                    <a href="<?php echo $val->link; ?>"><?php echo $val->text; ?></a>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
		</li>
	<?php endif; ?>

	<?php require_once(TPLDIR . "login.tpl.php") ?>
</ul>