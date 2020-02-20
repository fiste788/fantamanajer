<?php

/**
 * @var \App\View\AppView $this
 * @var string $controller_name
 * @var string $title
 * @var string $view_name
 */
?>
<!doctype html>
<html class="no-js">

<head>
    <?= $this->Html->charset() ?>
    <title>
        FantaManajer
        <?= $title ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $this->Html->meta('icon') ?>


    <?= $this->fetch('meta') ?>
    <?= $this->AssetCompress->css('main'); ?>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
        <header class="mdl-layout__header mdl-layout__header--waterfall">
            <?= $this->element('header') ?>
        </header>
        <div class="mdl-layout__drawer">
            <?php if ($this->request->session()->read('logged')) : ?>
                <header class="demo-drawer-header">
                    <i class="material-icons md-48">account_circle</i>
                    <div class="demo-avatar-dropdown">
                        <span><?php echo $this->request->session()->read('Auth.User.email') ?></span>
                    </div>
                </header>
            <?php endif; ?>
            <nav class="mdl-navigation">
                <?= $this->element('navbar') ?>
            </nav>
        </div>
        <main class="mdl-layout__content">
            <div class="page-content" id="<?= $controller_name . '_' . $view_name; ?>">
                <?= $this->fetch('content') ?>
            </div>
            <?php if ($this->request->session()->read('logged')) : ?>
                <div id="fab_ctn" class="mdl-button--fab_flinger-container">
                    <button id="fab_btn" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
                        <i class="material-icons">add</i>
                    </button>
                    <div class="mdl-button--fab_flinger-options">
                        <a href="<?php echo $this->Url->build('lineups') ?>" class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-js-ripple-effect">
                            <i class="material-icons">star_rate</i>
                            <span class="mdl-button__text">Inserisci formazione</span>
                        </a>
                        <a href="<?php echo $this->Url->build(['controller' => 'Articles', 'action' => 'add']) ?>" class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-js-ripple-effect">
                            <i class="material-icons">message</i>
                            <span class="mdl-button__text">Nuova conferenza stampa</span>
                        </a>
                        <a href="<?php echo $this->Url->build(['controller' => 'Teams', 'action' => 'view', 'id' => $this->request->session()->read('Team')->id]) . '#tab_transfert' ?>" class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-js-ripple-effect">
                            <i class="material-icons">swap_vert</i>
                            <span class="mdl-button__text">Nuovo trasferimento</span>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            <footer class="mdl-mini-footer">
                <div class="mdl-mini-footer__left-section">
                    <div class="mdl-logo">FantaManajer</div>
                    <ul class="mdl-mini-footer__link-list">
                        <li><a href="#">Help</a></li>
                        <li><a href="#">Privacy & Terms</a></li>
                    </ul>
                </div>
            </footer>

        </main>
    </div>
    <?= $this->AssetCompress->script('combined'); ?>
    <?= $this->fetch('scriptBottom') ?>
</body>

</html>
