<!doctype html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <title>FantaManajer<?php if (isset($this->title)) echo " - " . $this->title; ?></title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
        <meta name="verify-v1" content="CkLFVD0+jN20Tcmm4kHQmzRijDZbny9QgKZcxkLaCl8=" />
        <meta name="description" content="Fantamanajer: un semplice tool online scritto in php che ti permette di gestire al meglio il tuo torneo di fantacalcio." />
        <meta name="author" content="Stefano Sonzogni"/>
        <meta name="keywords" content="fantamanajer,alzano sopra" />
        <meta name="application-name" content="FantaManajer"/>
        <meta name="msapplication-config" content="http://fantamanajer.it/browserconfig.xml" />
        <meta name="msapplication-TileImage" content="/public/images/fantamanajer.png"/>
        <meta name="msapplication-task" content="name=Classifica;action-uri=<?php echo $this->router->generate('ranking') ?>;icon-uri=/favicon.ico" />
        <meta name="msapplication-task" content="name=Clubs;action-uri=<?php echo $this->router->generate('clubs_index') ?>;icon-uri=/favicon.ico" />
        <meta name="msapplication-starturl" content="http://fantamanajer.it" />
        <meta name="msapplication-tooltip" content="FantaManajer - Gestisci la tua lega del fantacalcio" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="mobile-web-app-capable" content="yes" />
        <meta property="og:title" content="FantaManajer" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="http://fantamanajer.it" />
        <meta property="og:image" content="" />
        <meta property="og:description" content="Gestisci la tua lega del fantacalcio con il FantaManajer"/>
        <meta property="og:site_name" content="FantaManajer" />
        <meta property="fb:admins" content="sonzogni.stefano" />
        <meta name="fb:page_id" content="351655380347" />
        <meta name="google-signin-client_id" content="852085834364-e70140tqjo75h5ht2ustt490hjhgkruv.apps.googleusercontent.com">
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
        <?php if (isset($this->quickLinks)): ?>
            <?php if ($this->quickLinks->prev != FALSE): ?>
                <link rel="prev" href="<?php echo $this->quickLinks->prev->href; ?>" title="<?php echo $this->quickLinks->prev->title; ?>" />
            <?php endif; ?>
            <?php if ($this->quickLinks->next != FALSE): ?>
                <link rel="next" href="<?php echo $this->quickLinks->next->href; ?>" title="<?php echo $this->quickLinks->next->title; ?>" />
            <?php endif ?>
        <?php endif ?>
        <?php if (LOCAL): ?>
            <?php foreach ($this->generalCss as $key => $val): ?>
                <link href="<?php echo CSSURL . $val; ?>" media="screen" rel="stylesheet" type="text/css" />
            <?php endforeach; ?>
        <?php else: ?>
            <link href="<?php echo CSSURL . 'combined.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
        <?php endif; ?>
        <?php if (isset($this->css)): ?>
            <?php foreach ($this->css as $key => $val): ?>
                <link href="<?php echo CSSURL . $val . '.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
            <?php endforeach; ?>
        <?php endif; ?>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
              rel="stylesheet">
        <!--[if gte IE 9]><style type="text/css">.gradient {filter: none;}</style><![endif]-->
        <link href="https://plus.google.com/107850880885578143642" rel="publisher" />

    </head>
    <body>
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
            <header class="mdl-layout__header mdl-layout__header--waterfall">
                <?php echo $this->header ?>
            </header>
            <div class="mdl-layout__drawer">
                <?php if ($_SESSION['logged']) : ?>
                    <header class="demo-drawer-header">
                        <i class="material-icons md-48">account_circle</i>
                        <div class="demo-avatar-dropdown">
                            <span><?php echo $_SESSION['email'] ?></span>
                        </div>
                    </header>
                <?php endif; ?>
                <nav class="mdl-navigation">
                    <?php echo $this->navbar; ?>
                </nav>
            </div>
            <main class="mdl-layout__content">
                <div class="page-content" id="<?= isset($this->page) ? $this->page->name : ""?>">
                    <?php echo $this->content; ?>
                </div>
                <?php if($_SESSION['logged']): ?>
                    <div id="fab_ctn" class="mdl-button--fab_flinger-container">
                        <button id="fab_btn" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
                            <i class="material-icons">add</i>
                        </button>
                        <div class="mdl-button--fab_flinger-options">
                            <a href="<?php echo $this->router->generate('lineups') ?>" class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-js-ripple-effect">
                                <i class="material-icons">star_rate</i>
                                <span class="mdl-button__text">Inserisci formazione</span>
                            </a>
                            <a href="<?php echo $this->router->generate('articles_new') ?>" class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-js-ripple-effect">
                                <i class="material-icons">message</i>
                                <span class="mdl-button__text">Nuova conferenza stampa</span>
                            </a>
                            <a href="<?php echo $this->router->generate('teams_show',['id' => $_SESSION['team']->id]) . '#tab_transfert' ?>" class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-js-ripple-effect">
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
        <div id="snackbar" aria-live="assertive" aria-atomic="true" aria-relevant="text" class="mdl-js-snackbar"></div>
        <?php if (LOCAL): ?>
            <script type="text/javascript">
                var LOCAL = <?php echo (LOCAL) ? 'true' : 'false' ?>;
                var JSURL = '<?php echo JSURL ?>';
                var PUBLICURL = '<?php echo PUBLICURL ?>';
                var IMGSURL = '<?php echo IMGSURL ?>';
                var FULLURL = '<?php echo FULLURL ?>';
            </script>
            <?php foreach ($this->generalJs as $key => $val): ?>
                <script src="<?php echo $val; ?>" type="text/javascript"></script>
            <?php endforeach; ?>
            <?php echo $this->js; ?>
        <?php else: ?>
            <script src="<?php echo JSURL . 'combined/combined.js'; ?>" type="text/javascript"></script>
            <?php if (file_exists(JAVASCRIPTSDIR . 'combined/' . $this->route['name'] . '.js')): ?>
                <script src="<?php echo JSURL . 'combined/' . $this->route['name'] . '.js'; ?>" type="text/javascript"></script>
            <?php endif; ?>
        <?php endif; ?>
        <script src="https://apis.google.com/js/platform.js" async defer></script>
    </body>
</html>
