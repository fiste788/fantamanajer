<?php if($_SESSION['logged'] != TRUE): ?>
	<div id="login" class="right last">
		<div class="box-top-sx column last">
		<div class="box-top-dx column last">
		<div class="box-bottom-sx column last">
		<div class="box-bottom-dx column last">
			<form id="loginform" action="index.php?<?php echo str_replace('&','&amp;',$_SERVER['QUERY_STRING']); ?>" method="post" name="loginform">
				<h3>Login</h3>
					<div class="field">
						<label for="username">Username:</label>
						<input class="text" id="username" maxlength="12" type="text" name="username" />
					</div>
					<div class="field">
						<label for="password">Password:</label>
						<input class="text" id="password" type="password" maxlength="12" name="password" />
					</div>
				<input class="submit" type="submit" name="login" value="Entra" />
			</form>
		</div>
		</div>
		</div>
		</div>
	<?php if(isset($this->loginerror)): ?>
		<?php echo $this->loginerror; ?>
	<?php endif; ?>
	</div>
<?php elseif($_SESSION['logged']): ?>
	<a class="logout right" href="index.php?p=home&amp;logout=TRUE" title="Logout">Logout</a>
<?php endif; ?>
