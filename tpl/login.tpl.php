<div id="login" class="right last">
	<div id="login-left" class="right last"></div>
	<div id="login-center" class="right last">
	<?php $appo = $_GET; unset($appo['p']); ?>
	<?php if(count($this->leghe) > 1): ?>
		<form class="column" action="<?php echo Links::getLink($this->p,$appo); ?>" method="post">
			<fieldset>
				<label class="lega" for="legaView">Lega:</label>
				<select id="legaView" onchange="this.form.submit();" name="legaView">
					<?php foreach($this->leghe as $key=>$value): ?>
						<option <?php echo ($_SESSION['legaView'] == $value->idLega) ? ' selected="selected"' : ''; ?> value="<?php echo $value->idLega; ?>"><?php echo $value->nomeLega; ?></option>
					<?php endforeach; ?>
				</select>
			</fieldset>
		</form>
	<?php endif; ?>
	<?php if($_SESSION['logged'] != TRUE): ?>
		<form class="column last" action="<?php echo Links::getLink('home'); ?>" method="post">
			<fieldset>
				<div class="field column">
					<label for="username">Username:</label>
					<input class="text" id="username" maxlength="12" type="text" name="username" />
				</div>
				<div class="field column">
					<label for="password">Password:</label>
					<input class="text" id="password" type="password" maxlength="12" name="password" />
				</div>
				<input class="submit" type="submit" name="login" value="" />
			</fieldset>
		</form>
	<?php elseif($_SESSION['logged']): ?>
		<div class="field column">
			<a class="logout column" href="<?php echo Links::getLink('home',array('logout'=>TRUE)); ?>" title="Logout">Logout</a>
		</div>
	<?php endif; ?>
	</div>
	<div id="login-right" class="right last"></div>
</div>
