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
        <meta name="msapplication-task" content="name=Classifica;action-uri=<?php echo $this->router->generate('classifica') ?>;icon-uri=/favicon.ico" />
        <meta name="msapplication-task" content="name=Clubs;action-uri=<?php echo $this->router->generate('club_index') ?>;icon-uri=/favicon.ico" />
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
        
        <?php if(isset($this->quickLinks)): ?>
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
        <!--[if gte IE 9]><style type="text/css">.gradient {filter: none;}</style><![endif]-->
        <link href="https://plus.google.com/107850880885578143642" rel="publisher" />
        <link rel="alternate" type="application/atom+xml" title="FantaManajer - RSS" href="<?php echo $this->router->generate('rss',array('lega'=>$_SESSION['legaView'])) ?>" />
    </head>
    <body>
        <div id="container">
            <div id="topbar" class="navbar navbar-inverse navbar-fixed-top">
                <?php echo $this->navbar; ?>
            </div>
            <?php require_once(LAYOUTSDIR . "message.php"); ?>
            <header class="hidden-xs">
                <div class="gradient">
                    <div class="container"><?php echo $this->header; ?></div>
                </div>
            </header>
            <!--[if lte IE 8]>
                <p class="browsehappy">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> to better experience this site.</p>
            <![endif]-->
            <?php if (!empty($this->operation)): ?>
                <section id="operation">
                    <div class="fix">
                        <div class="container">
                            <div class="operation-content">
                                <?php if ($this->quickLinks->prev != FALSE): ?>
                                    <a rel="prev" class="back" href="<?php echo $this->quickLinks->prev->href; ?>" title="<?php echo $this->quickLinks->prev->title; ?>"><span class="glyphicon glyphicon-arrow-left"></span></a>
                                <?php else: ?>
                                    <div class="back"><span></span></div>
                                <?php endif; ?>
                                <div class="center"><?php echo $this->operation; ?></div>
                                <?php if ($this->quickLinks->next != FALSE): ?>
                                    <a rel="next" class="next" href="<?php echo $this->quickLinks->next->href; ?>" title="<?php echo $this->quickLinks->next->title; ?>"><span class="glyphicon glyphicon-arrow-right"></span></a>
                                <?php else: ?>
                                    <div class="next"><span></span></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
            <section id="content">
                <div class="container" id="<?php echo $this->route['name']; ?>">
                    <div class="inner-page"><?php echo $this->content; ?></div>
                </div>
            </section>
        </div>
        <footer>
            <div class="container">
                <?php echo $this->footer; ?>
            </div>
        </footer>
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
            <?php if (isset($this->js)): ?>
                <?php foreach ($this->js as $key => $val): ?>
                    <?php if (is_array($val)): ?>
                        <?php foreach ($val as $val2): ?>
                            <script src="<?php echo JSURL . $key . '/' . $val2 . '.js'; ?>" type="text/javascript"></script>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <script src="<?php echo JSURL . $key . '/' . $val . '.js'; ?>" type="text/javascript"></script>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (file_exists(JAVASCRIPTSDIR . 'pages/' . $this->route['name'] . '.js')): ?>
                <script src="<?php echo JSURL . 'pages/' . $this->route['name'] . '.js'; ?>" type="text/javascript"></script>
            <?php endif; ?>
        <?php else: ?>
            <script src="<?php echo JSURL . 'combined/combined.js'; ?>" type="text/javascript"></script>
            <?php if (file_exists(JAVASCRIPTSDIR . 'combined/' . $this->route['name'] . '.js')): ?>
                <script src="<?php echo JSURL . 'combined/' . $this->route['name'] . '.js'; ?>" type="text/javascript"></script>
            <?php endif; ?>
        <?php endif; ?>
    </body>
</html>
