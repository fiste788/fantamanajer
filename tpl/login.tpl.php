<?php 
require_once(INCDIR . 'links.inc.php');
$linksObj = new links(); 
?>
<div id="login" class="right last">
	<div id="login-left" class="column last"></div>
	<div id="login-center" class="column last">
	<?php $appo = $_GET; unset($appo['p']); ?>
		<?php if(count($this->leghe) > 1): ?>
		<form class="column" name="legheView" action="<?php echo $linksObj->getLink($this->p,$appo); ?>" method="post">
			<label class="lega" for="legaView">Lega:</label>
			<select id="legaView" onchange="document.legheView.submit();" name="legaView">
				<?php foreach($this->leghe as $key=>$value): ?>
					<option <?php if($_SESSION['legaView'] == $value['idLega']) echo ' selected="selected"'; ?> value="<?php echo $value['idLega']; ?>"><?php echo $value['nomeLega']; ?></option>
				<?php endforeach; ?>
			</select>
		</form>
		<?php endif; ?>
	<?php if($_SESSION['logged'] != TRUE): ?>
		<?php if(isset($this->loginerror)): ?>
			<div id="messaggio" class="messaggio column last">
				<span><?php echo $this->loginerror; ?></span>
			</div>
		<?php endif; ?>
			<form class="column last" id="loginform" action="<?php echo $linksObj->getLink('home'); ?>" method="post" name="loginform">
					<div class="field column">
						<label for="username">Username:</label>
						<input class="text" id="username" maxlength="12" type="text" name="username" />
					</div>
					<div class="field column">
						<label for="password">Password:</label>
						<input class="text" id="password" type="password" maxlength="12" name="password" />
					</div>
				<input class="submit" type="submit" name="login" value="" />
			</form>
	<?php elseif($_SESSION['logged']): ?>
		<div class="field column">
			<a class="logout right" href="<?php echo $linksObj->getLink('home',array('logout'=>TRUE)); ?>" title="Logout">Logout</a>
		</div>
	<?php endif; ?>
	</div>
	<div id="login-right" class="right last"></div>
</div>