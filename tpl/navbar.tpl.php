<div class="container">
	<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</a>
    <a class="brand hidden-desktop" href="/">FantaManajer</a>
	<div class="nav-collapse">
		<ul class="nav">
			<?php foreach($this->entries->navbar as $key=>$val): ?>
				<?php if($this->entries->pages[$key]->roles <= $_SESSION['roles']): ?>
					<li<?php if(!empty($val) || in_array($this->request->get('p'),$this->entries->pages[$key]->pages)) { echo ' class="'; if(!empty($val)) echo 'dropdown'; if(in_array($this->request->get('p'),$this->entries->pages[$key]->pages)) echo ' active'; echo '"';} ?>>
						<a class="level<?php if(!empty($val)) echo " dropdown-toggle"; ?>" href="<?php echo Links::getLink($key) ?>"><?php echo $this->entries->pages[$key]->title; ?></a>
						<?php if(!empty($val)): ?>
							<ul class="dropdown-menu subnav">
							<?php foreach($val as $key2=>$val2): ?>
								<li><a href="<?php echo Links::getLink($val2); ?>"><?php echo $this->entries->pages[$val2]->title; ?></a></li>
							<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>

			<?php require_once(TPLDIR . "login.tpl.php") ?>

			<?php if($_SESSION['logged']): ?>
                <li id="account" class="pull-right dropdown">
                    <a id="notifiche" <?php if(!empty($this->notifiche)) echo ' class="dropdown-toggle nopick"'; ?>>
                        <div class="hidden-desktop">Notifiche: <span class="badge badge-important"><?php echo count($this->notifiche); ?></span></div>
		                <span class="visible-desktop<?php if(!empty($this->notifiche)) echo ' active'; ?>" title="Clicca per vedere le notifiche"><?php echo count($this->notifiche); ?></span>
		            </a>
		            <?php if(!empty($this->notifiche)): ?>
		                <ul class="boxNotifiche dropdown-menu">
		                <?php foreach($this->notifiche as $key=>$val): ?>
                            <li><a href="<?php echo $val->link; ?>"><?php echo $val->text; ?></a></li>
		                <?php endforeach; ?>
		                </ul>
		            <?php endif; ?>
				</li>
			<?php endif; ?>

			<?php if(count($this->leghe) > 1): ?>
			<li id="legaSelect" class="pull-right">
				<?php $appo = $_GET; unset($appo['p']); ?>
				<form class="form-inline" action="<?php echo Links::getLink($this->request->get('p'),$appo); ?>" method="post">
					<fieldset>
                        <label for="legaView" class="hidden-desktop">Seleziona la lega</label>
						<select id="legaView" onchange="this.form.submit();" class="input-medium" name="legaView">
							<?php foreach($this->leghe as $key=>$value): ?>
								<option <?php echo ($_SESSION['legaView'] == $key) ? ' selected="selected"' : ''; ?> value="<?php echo $key; ?>"><?php echo $value->getNome(); ?></option>
							<?php endforeach; ?>
						</select>
					</fieldset>
				</form>
				</li>
			<?php endif; ?>
		</ul>
	</div>
</div>