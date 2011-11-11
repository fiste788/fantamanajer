<div id="login" class="entry">
	<?php if(!$_SESSION['logged']): ?>
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
	<?php else: ?>
		<a class="logout column" href="<?php echo Links::getLink('home',array('logout'=>TRUE)); ?>" title="Logout">Logout</a>
	<?php endif; ?>
	</div>
</div>
