<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

	<head>
	    <meta charset="utf-8">
	    <title>FantaManajer<?php if(isset($this->title)) echo " - " . $this->title; ?></title>
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<meta name="verify-v1" content="CkLFVD0+jN20Tcmm4kHQmzRijDZbny9QgKZcxkLaCl8=" />
		<meta name="description" content="Fantamanajer: un semplice tool online scritto in php che ti permette di gestire al meglio il tuo torneo di fantacalcio." />
		<meta name="author" content="Stefano Sonzogni"/>
		<meta name="keywords" content="fantamanajer,alzano sopra" />
		<?php if(LOCAL): ?>
			<?php foreach($this->generalCss as $key => $val): ?>
				<link href="<?php echo CSSURL . $val; ?>" media="screen" rel="stylesheet" type="text/less" />
			<?php endforeach; ?>
		<?php else: ?>
			<link href="<?php echo CSSURL . 'combined.css?v=' . VERSION; ?>" media="screen" rel="stylesheet" type="text/css" />
		<?php endif; ?>
		<?php if(isset($this->css)): ?>
		<?php foreach($this->css as $key => $val): ?>
			<link href="<?php echo CSSURL . $val . '.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
		<?php endforeach; ?>
		<?php endif; ?>
		<?php if(file_exists(CSSDIR . 'pages-' . $this->p . ".css")): ?>
			<link href="<?php echo CSSURL . 'pages-' . $this->p . '.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
		<?php endif; ?>
        <!--[if gte IE 9]><style type="text/css">.gradient {filter: none;}</style><![endif]-->
		<!--[if lt IE 8]><link rel="stylesheet" href="<?php echo CSSURL . 'ie.min.css'; ?>" type="text/css" media="screen"><![endif]-->
		<link href="<?php echo IMGSURL . 'favicon.ico' ?>" rel="shortcut icon" type="image/x-icon" />
		<link rel="alternate" type="application/atom+xml" title="FantaManajer - RSS" href="<?php echo FULLURL . 'rss.php?lega=' . $_SESSION['legaView']; ?>" />
		<link rel="alternate" href="<?php echo FULLURL . 'rssPicLens.php'; ?>" type="application/rss+xml" title="Squadre" id="gallery" />
		 <script src="<?php echo JSURL ?>/modernizr/modernizr-2.0.6.min.js"></script>
	</head>
	<?php flush(); ?>
	<body>
		<nav id="topbar" class="navbar navbar-fixed-top">
			<div class="page"><?php echo $this->navbar; ?></div>
		</nav>
		<header>
			<div class="gradient">
                <div class="page"><?php echo $this->header; ?></div>
            </div>
		</header>
		<?php require_once(TPLDIR . "message.tpl.php"); ?>
        <?php if(!empty($this->operation)): ?>
    		<section id="operation">
				<div class="fix">
	                <div class="page">
						<div class="operationContent">
		                    <?php if($this->quickLinks->prev != FALSE): ?>
		    					<a class="back" href="<?php echo $this->quickLinks->prev->href; ?>" title="<?php echo $this->quickLinks->prev->title; ?>"><span class="icon-arrow-left"></span></a>
							<?php else: ?>
								<div class="back">&nbsp;</div>
							<?php endif; ?>
		    				<div class="center"><?php echo $this->operation; ?></div>
		                    <?php if($this->quickLinks->next != FALSE): ?>
		    					<a class="next" href="<?php echo $this->quickLinks->next->href; ?>" title="<?php echo $this->quickLinks->next->title; ?>"><span class="icon-arrow-right"></span></a>
							<?php else: ?>
								<div class="next">&nbsp;</div>
		    				<?php endif; ?>
						</div>
	                </div>
				</div>
            </section>
        <?php endif; ?>
		<section id="content">
    		<div class="page" id="<?php echo $this->p; ?>">
    			<div class="innerPage"><?php echo $this->content; ?></div>
            </div>
		</section>
		<footer>
			<div class="page">
				<?php echo $this->footer; ?>
			</div>
		</footer>
		<?php if(isset($this->ieHack)): ?>
			<?php foreach($this->ieHack as $key=>$val): ?>
				<!--[if IE]><script src="<?php echo JSURL . $key . '/' . $val; ?>" type="text/javascript"></script><![endif]-->
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if(LOCAL): ?>
			<?php foreach($this->generalJs as $key => $val): ?>
				<script src="<?php echo JSURL . $val; ?>" type="text/javascript"></script>
			<?php endforeach; ?>
			<?php if(isset($this->js)): ?>
			<?php foreach($this->js as $key => $val): ?>
				<?php if(is_array($val)): ?>
					<?php foreach($val as $key2=>$val2): ?>
						<script src="<?php echo JSURL . $key . '/' . $val2 . '.js'; ?>" type="text/javascript"></script>
					<?php endforeach; ?>
				<?php else: ?>
					<script src="<?php echo JSURL . $key . '/' . $val . '.js'; ?>" type="text/javascript"></script>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php endif; ?>
			<?php if(file_exists(JSDIR . 'pages/' . $this->p  . '.js')): ?>
				<script src="<?php echo JSURL . 'pages/' . $this->p . '.js'; ?>" type="text/javascript"></script>
			<?php endif; ?>
		<?php else: ?>
		<script src="<?php echo JSURL . 'combined/combined.js?v=' . VERSION; ?>" type="text/javascript"></script>
		<?php if(file_exists(JSDIR . 'combined/' . $this->p . '.js')): ?>
			<script src="<?php echo JSURL . 'combined/' . $this->p . '.js?v=' . VERSION; ?>" type="text/javascript"></script>
		<?php endif; ?>
<script type="text/javascript">
// <![CDATA[
$.trackPage("UA-3016148-1");if(jQuery.browser.msie && jQuery.browser.version<7)window.location="error_docs/not_supported.html";
// ]]>
</script>
		<?php endif; ?>
	</body>
</html>
