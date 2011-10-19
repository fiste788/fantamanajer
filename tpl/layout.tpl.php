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
		<link href="http://fonts.googleapis.com/css?family=Droid+Sans&amp;subset=latin" rel="stylesheet" type="text/css" />
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<meta name="verify-v1" content="CkLFVD0+jN20Tcmm4kHQmzRijDZbny9QgKZcxkLaCl8=" />
		<meta name="description" content="Fantamanajer: un semplice tool online scritto in php che ti permette di gestire al meglio il tuo torneo di fantacalcio." />
		<meta name="author" content="Stefano Sonzogni"/>
		<meta name="keywords" content="fantamanajer,alzano sopra" />
		<?php if(LOCAL): ?>
			<?php foreach($this->generalCss as $key => $val): ?>
				<link href="<?php echo CSSURL . $val; ?>" media="screen" rel="stylesheet" type="text/css" />
			<?php endforeach; ?>
		<?php else: ?>
			<link href="<?php echo CSSURL . 'combined.css?v=' . VERSION; ?>" media="screen" rel="stylesheet" type="text/css" />
		<?php endif; ?>
		<?php if(isset($this->css)): ?>
		<?php foreach($this->css as $key => $val): ?>
			<link href="<?php echo CSSURL . $val . '.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
		<?php endforeach; ?>
		<?php endif; ?>
		<!--[if lt IE 8]><link rel="stylesheet" href="<?php echo CSSURL . 'ie.min.css'; ?>" type="text/css" media="screen"><![endif]-->
		<link href='http://fonts.googleapis.com/css?family=Slackey' rel='stylesheet' type='text/css'>
		<link href="<?php echo IMGSURL . 'favicon.ico' ?>" rel="shortcut icon" type="image/x-icon" />
		<link rel="alternate" type="application/atom+xml" title="FantaManajer - RSS" href="<?php echo FULLURL . 'rss.php?lega=' . $_SESSION['legaView']; ?>" />
		<link rel="alternate" href="<?php echo FULLURL . 'rssPicLens.php'; ?>" type="application/rss+xml" title="Squadre" id="gallery" />
		 <script src="<?php echo JSURL ?>/modernizr/modernizr-2.0.6.min.js"></script>
	</head>
	<?php flush(); ?>
	<body>
		<nav>
			<?php echo $this->navbar ?>
		</nav>
			<header>
				<?php echo $this->header; ?>
			</header>
		
		<section id="content">

    <?php if($this->message->show || isset($this->generalMessage)): ?>
					<div id="messaggioContainer" title="Clicca per nascondere">
					<?php if(isset($this->generalMessage)): ?>
						<div title="Clicca per nascondere" class="messaggio error"><?php echo $this->generalMessage; ?></div>
					<?php endif; ?>
					<?php if($this->message->show): ?>
						<?php switch($this->message->level):
							 case 0: ?>
							<div class="messaggio success"><?php echo $this->message->text; ?></div>
							<?php break; case 1: ?>
							<div class="messaggio warning"><?php echo $this->message->text; ?></div>
							<?php break; case 2: ?>
							<div class="messaggio error"><?php echo $this->message->text; ?></div>
							<?php endswitch; ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div id="<?php echo $this->p; ?>">
					<?php echo $this->content; ?>
				</div>
		</section>
		<footer>
			<?php echo $this->footer; ?>
		</footer>
		<?php if(!empty($this->quickLinks) || !empty($this->operation)): ?>
		<div id="topRightBar"<?php if(!empty($this->operation)) echo ' class="active"'; ?>>
			<div>
				<?php if(!empty($this->operation)): ?>
				<div title="Mostra menu" id="click-menu">
					<span>Menu</span>
				</div>
				<?php endif; ?>
				<?php if(isset($this->quickLinks->prec) && $this->quickLinks->prec != FALSE): ?>
					<a class="back" href="<?php echo $this->quickLinks->prec->href; ?>" title="<?php echo $this->quickLinks->prec->title; ?>">&nbsp;</a>
				<?php endif; ?>
				<?php if(isset($this->quickLinks->succ) && $this->quickLinks->succ != FALSE): ?>
					<a class="next" href="<?php echo $this->quickLinks->succ->href; ?>" title="<?php echo $this->quickLinks->succ->title; ?>">&nbsp;</a>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>	
		<?php if(!empty($this->operation)): ?>
			<div id="menu"><?php echo $this->operation; ?></div>
		<?php endif; ?>
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
