<div class="mdl-layout__header-row">
    <span class="mdl-layout-title">FantaManajer > <?php if (isset($this->page)) echo $this->page->title ?></span>
</div>
<div class="mdl-layout__header-row">
        <?php if (isset($this->page) && isset($this->entries->navbar[$this->page->category]) && !empty($this->entries->navbar[$this->page->category])): ?>
        <div class="mdl-layout-spacer"></div>
        <nav class="mdl-navigation">
            <?php foreach ($this->entries->navbar[$this->page->category] as $val2): ?>
                <a class="mdl-navigation__link" href="<?php echo isset($this->entries->pages[$val2]) ? $this->router->generate($val2) : ""; ?>"><?php echo $this->entries->pages[$val2]->title; ?></a>
            <?php endforeach; ?>
        </nav>
    <?php endif; ?>
</div>
<?php if (!$this->isSeasonEnded): ?>
    <div class="pull-right hidden" id="countdown" data-data-fine="<?php echo $this->timestamp ?>">Tempo rimanente per la formazione<br />
        <div><?php echo $this->dataFine['year'] . '-' . ($this->dataFine['month'] - 1) . '-' . $this->dataFine['day'] . ' ' . $this->dataFine['hour'] . ':' . $this->dataFine['minute'] . ':' . $this->dataFine['second']; ?></div>
    </div>
<?php endif; ?>
