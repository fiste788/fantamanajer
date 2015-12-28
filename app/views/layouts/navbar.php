<?php if(!$_SESSION['logged']): ?>
    <a class="mdl-navigation__link" href="<?php echo $this->router->generate("login") ?>">Login</a>
<?php endif; ?>
<?php foreach ($this->entries->navbar as $key => $val): ?>
    <?php if ($this->entries->pages[$key]->roles <= $_SESSION['roles']): ?>
        <?php if(empty($val)): ?>
            <a class="mdl-navigation__link <?php if (isset($this->page) && in_array($this->page->name, $this->entries->pages[$key]->pages)) echo ' mdl-navigation__link--current'; ?>" href="<?php echo isset($this->entries->pages[$key]) ? $this->router->generate($key) : ""; ?>"><?php echo $this->entries->pages[$key]->title; ?></a>
        <?php else: ?>
            <div class="mdl-collapse<?php if (isset($this->page) && in_array($this->page->name, $this->entries->pages[$key]->pages)) echo ' mdl-collapse--opened' ?>"> 
                <a class="mdl-navigation__link mdl-collapse__button<?php if (isset($this->page) && in_array($this->page->name, $this->entries->pages[$key]->pages)) echo ' mdl-navigation__link--current'; ?>">
                    <i class="material-icons mdl-collapse__icon mdl-animation--default">expand_more</i>
                    <?php echo $this->entries->pages[$key]->title; ?>
                </a>
                <div class="mdl-collapse__content-wrapper">
                    <div class="mdl-collapse__content mdl-animation--default">
                        <?php foreach($val as $val2): ?>
                            <a class="mdl-navigation__link" href="<?php echo isset($this->entries->pages[$val2]) ? $this->router->generate($val2) : ""; ?>"><?php echo $this->entries->pages[$val2]->title; ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; ?>


<?php if ($_SESSION['logged']): ?>

    <a class="mdl-navigation__link" id="notifiche" <?php if (!empty($this->notification)) echo 'data-toggle="dropdown" class="dropdown-toggle"'; ?>>
        <span class="mdl-badge" data-badge="<?php echo count($this->notification); ?>">Notifiche</span>

    </a>
    <?php if (!empty($this->notification)): ?>
        <ul class="boxNotifiche dropdown-menu">
            <?php foreach ($this->notification as $key => $val): ?>
                <li><a href="<?php echo $val->link; ?>"><?php echo $val->text; ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>



<a class="mdl-navigation__link logout entry" href="<?php echo $this->router->generate('logout'); ?>" title="Logout">Logout</a>
<?php endif; ?>
<?php if (count($this->leagues) > 1): ?>   
    <form class="navbar-form navbar-right hidden-sm" action="<?php echo $this->router->generate('home'); ?>" method="post">
        <fieldset>
            <div class="form-group">
                <label for="lega-view" class="visible-xs control-label pull-left navbar-text">Seleziona la lega</label>
                <select id="lega-view" onchange="this.form.submit();" class="form-control input-medium" name="legaView">
                    <?php foreach ($this->leagues as $key => $value): ?>
                        <option <?php echo ($_SESSION['legaView'] == $key) ? ' selected="selected"' : ''; ?> value="<?php echo $key; ?>"><?php echo $value->getName(); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </fieldset>
    </form>
<?php endif; ?>
	