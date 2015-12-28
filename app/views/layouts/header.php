<div class="mdl-layout__header-row">
    <span class="mdl-layout-title"><?php echo (!empty($this->title)) ? $this->title : 'FantaManajer' ?></span>
    <?php if ($_SESSION['logged']): ?>
        <div class="mdl-layout-spacer"></div>
        <button id="notifications" class="mdl-color-text--white mdl-button">
            <i class="material-icons mdl-badge" data-badge="<?php echo count($this->notification) ?>">account_box</i>
        </button>

        <?php if (!empty($this->notification)): ?>
            <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu" for="notifications">
                <?php foreach ($this->notification as $key => $val): ?>
                    <li class="mdl-menu__item"><a href="<?php echo $val->link; ?>"><?php echo $val->text; ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php if (!empty($this->tabs)): ?>
    <?= $this->tabs ?>
<?php else: ?>
    <?php if (isset($this->page) && isset($this->entries->navbar[$this->page->category]) && !empty($this->entries->navbar[$this->page->category])): ?>
        <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
            <?php foreach ($this->entries->navbar[$this->page->category] as $val2): ?>
                <a class="mdl-layout__tab<?php if (isset($this->page) && $this->page->name == $this->entries->pages[$val2]->name) echo ' is-active'; ?>" href="<?php echo isset($this->entries->pages[$val2]) ? $this->router->generate($val2) : ""; ?>"><?php echo $this->entries->pages[$val2]->title; ?></a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php if (!$this->isSeasonEnded): ?>
    <div class="pull-right hidden" id="countdown" data-data-fine="<?php echo $this->timestamp ?>">Tempo rimanente per la formazione<br />
        <div><?php echo $this->dataFine['year'] . '-' . ($this->dataFine['month'] - 1) . '-' . $this->dataFine['day'] . ' ' . $this->dataFine['hour'] . ':' . $this->dataFine['minute'] . ':' . $this->dataFine['second']; ?></div>
    </div>
<?php endif; ?>
