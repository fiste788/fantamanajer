<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="mdl-layout__header-row">
    <span class="mdl-layout-title"><?php echo (!empty($title)) ? $title : 'FantaManajer' ?></span>
    <?php if ($this->request->session()->read('logged')): ?>
        <div class="mdl-layout-spacer"></div>
        <button id="notifications" class="mdl-color-text--white mdl-button">
            <i class="material-icons mdl-badge" data-badge="<?php echo "0" ?>">account_box</i>
        </button>

        <?php if (!empty($notification)): ?>
            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu" for="notifications">
                <?php foreach ($notification as $key => $val): ?>
                    <li class="mdl-menu__item"><a class="mdl-navigation__link" href="<?php echo $val->link; ?>"><?php echo $val->text; ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php if (!empty($tabs)): ?>
    <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
        <?php foreach($tabs as $key => $tab): ?>
            <a href="#tab_<?= $key ?>" class="mdl-layout__tab<?= isset($tab['selected']) ? ' is-active' : '' ?>"><?= $tab['label'] ?></a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <?php if (isset($page) && isset($entries->navbar[$this->page->category]) && !empty($entries->navbar[$page->category])): ?>
        <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
            <?php foreach ($entries->navbar[$page->category] as $val2): ?>
                <a class="mdl-layout__tab<?php if (isset($page) && $page->name == $entries->pages[$val2]->name) echo ' is-active'; ?>" href="<?php echo isset($entries->pages[$val2]) ? $router->generate($val2) : ""; ?>"><?php echo $entries->pages[$val2]->title; ?></a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
