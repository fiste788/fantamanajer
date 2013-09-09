<div class="container">
    <div class="navbar-header">
		<button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse" type="button">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand visible-xs" href="<?php echo $this->router->generate('home') ?>">FantaManajer</a>
	</div>
	<nav role="navigation" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <?php foreach($this->entries->navbar as $key=>$val): ?>
                <?php if($this->entries->pages[$key]->roles <= $_SESSION['roles']): ?>
                    <li<?php if(!empty($val) || in_array($this->request->getParam('p'),$this->entries->pages[$key]->pages)) { echo ' class="'; if(!empty($val)) echo 'dropdown'; if(in_array($this->request->getParam('p'),$this->entries->pages[$key]->pages)) echo ' active'; echo '"';} ?>>
                        <a class="level<?php if(!empty($val)) echo ' dropdown-toggle" data-toggle="dropdown'; ?>" href="<?php echo isset($this->entries->pages[$key]) ? $this->router->generate($key) : ""; ?>"><?php echo $this->entries->pages[$key]->title; ?></a>
                        <?php if(!empty($val)): ?>
                            <ul class="dropdown-menu" role="menu">
                                <?php foreach($val as $val2): ?>
                                    <li><a href="<?php echo isset($this->entries->pages[$val2]) ? $this->router->generate($val2) : ""; ?>"><?php echo $this->entries->pages[$val2]->title; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php if(count($this->leghe) > 1): ?>
                <li id="lega-select">
                    <form class="form-inline" action="<?php echo $this->router->generate('home'); ?>" method="post">
                        <fieldset>
                            <div class="form-group">
                                <label for="lega-view" class="visible-xs control-label col-xs-4">Seleziona la lega</label>
                                <select id="lega-view" onchange="this.form.submit();" class="form-control input-medium" name="legaView">
                                    <?php foreach($this->leghe as $key=>$value): ?>
                                        <option <?php echo ($_SESSION['legaView'] == $key) ? ' selected="selected"' : ''; ?> value="<?php echo $key; ?>"><?php echo $value->getNome(); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </fieldset>
                    </form>
                </li>
            <?php endif; ?>
            <?php if($_SESSION['logged']): ?>
                <li id="account" class="dropdown">
                    <a id="notifiche" <?php if(!empty($this->notifiche)) echo 'data-toggle="dropdown" class="dropdown-toggle"'; ?>>
                        <span class="visible-xs">Notifiche: </span>
                        <span class="label label-<?php if(!empty($this->notifiche)) echo 'danger'; else echo 'default' ?>" title="Clicca per vedere le notifiche"><?php echo count($this->notifiche); ?></span>
                    </a>
                    <?php if(!empty($this->notifiche)): ?>
                        <ul class="boxNotifiche dropdown-menu pull-right">
                        <?php foreach($this->notifiche as $key=>$val): ?>
                            <li><a href="<?php echo $val->link; ?>"><?php echo $val->text; ?></a></li>
                        <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endif; ?>

            <?php require_once(LAYOUTSDIR . "login.php") ?>
		</ul>
	</nav>
</div>