<li class="right dropdown" id="login">
	<?php if(!$_SESSION['logged']): ?>
		<a class="dropdown-toggle">Login</a>
		<ul class="dropdown-menu">
			<li>
				<form action="<?php echo Links::getLink('home'); ?>" method="post">
					<fieldset>
						<div class="field column">
							<label for="username">Username:</label>
							<input class="text" id="username" maxlength="12" type="text" name="username" />
						</div>
						<div class="field column">
							<label for="password">Password:</label>
							<input class="text" id="password" type="password" maxlength="12" name="password" />
						</div>
						<div class="field column">
							<input type="checkbox" name="remember" />
							<label for="remember">Ricorda</label>
						</div>
						<input class="btn-primary right" type="submit" name="login" value="OK" />
					</fieldset>
				</form>
			</li>
		</ul>
	<?php else: ?>
		<a class="logout entry" href="<?php echo Links::getLink('home',array('logout'=>TRUE)); ?>" title="Logout">Logout</a>
	<?php endif; ?>
</li>
