<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>FantaManajer<?php if(isset($this->title)) echo " - " . $this->title; ?></title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<meta name="verify-v1" content="CkLFVD0+jN20Tcmm4kHQmzRijDZbny9QgKZcxkLaCl8=" />
		<meta name="description" content="Fantamanajer: un semplice tool online scritto in php che ti permette di gestire al meglio il tuo torneo di fantacalcio." />
		<meta name="author" content="Stefano Sonzogni"/>
		<meta name="keywords" content="fantamanajer,alzano sopra" />
		<meta name="robots" content="index,follow" />
		<link href="<?php echo CSSURL . (LOCAL ? 'screen.css' : 'screen.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
		<link href="<?php echo CSSURL . (LOCAL ? 'print.css' : 'print.min.css'); ?>" media="print" rel="stylesheet" type="text/css" />
		<?php if(isset($this->css)): ?>
		<?php foreach($this->css as $key => $val): ?>
			<link href="<?php echo CSSURL . $val . '.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
		<?php endforeach; ?>
		<?php endif; ?>
		<!--[if IE]><link rel="stylesheet" href="<?php echo CSSURL . 'ie.min.css';?>" type="text/css" media="screen"><![endif]-->
		<link href="<?php echo IMGSURL . 'favicon.ico' ?>" rel="shortcut icon" type="image/x-icon" />
		<link rel="alternate" type="application/atom+xml" title="FantaManajer - RSS" href="<?php echo FULLURL . 'rss.php?lega=' . $_SESSION['legaView']; ?>" />
		<link rel="alternate" href="<?php echo FULLURL . 'rssPicLens.php'; ?>" type="application/rss+xml" title="Squadre" id="gallery" />
	</head>
	<?php flush(); ?>
	<body>
		<?php if(DEBUG): ?>
		<div id="debugShow">Debug</div>
		<div id="debug">
			<h3>Debug</h3>
			<?php echo $this->debug; ?>
		</div>
		<?php endif; ?>
		<div id="big-container">
			<div id="header" class="column last">
				<?php echo $this->header; ?>
			</div>
			<?php require('login.tpl.php'); ?>
			<div id="navbar" class="column last">
				<?php echo $this->navbar ?>
			</div>
			<div id="content" class="column last">
				<?php if($this->message->show || isset($this->generalMessage)): ?>
					<div id="messaggioContainer" title="Clicca per nascondere">
					<?php if(isset($this->generalMessage)): ?>
						<div title="Clicca per nascondere" class="messaggio error column last">
							<div class="column last top"></div>
							<div class="column last middle">
								<img alt="!" src="<?php echo IMGSURL . 'attention-bad.png'; ?>" title="Attenzione!" />
								<span><?php echo $this->generalMessage; ?></span>
							</div>
							<div class="column last bottom"></div>
						</div>
					<?php endif; ?>
					<?php if($this->message->show): ?>
						<?php switch($this->message->level): 
							 case 0: ?>
							<div class="messaggio success column last">
								<div class="column last top"></div>
								<div class="column last middle">
									<img alt="OK" height="56" src="<?php echo IMGSURL . 'ok.png'; ?>" width="56" />
									<span><?php echo $this->message->text; ?></span>
								</div>
								<div class="column last bottom"></div>
							</div>
							<?php break; case 1: ?>
							<div class="messaggio warning column last">
								<div class="column last top"></div>
								<div class="column last middle">
									<img alt="!" height="56" src="<?php echo IMGSURL . 'attention.png'; ?>" width="56" />
									<span><?php echo $this->message->text; ?></span>
								</div>
								<div class="column last bottom"></div>
							</div>
							<?php break; case 2: ?>
							<div class="messaggio error column last">
								<div class="column last top"></div>
								<div class="column last middle">
									<img alt="!" height="56" src="<?php echo IMGSURL . 'attention-bad.png'; ?>" width="56" />
									<span><?php echo $this->message->text; ?></span>
								</div>
								<div class="column last bottom"></div>
							</div>
							<?php endswitch; ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div id="<?php echo $this->p; ?>" class="main-content">
					<?php echo $this->content; ?>
				</div>
			</div>
			<div id="footer">
				<?php echo $this->footer; ?>
			</div>
		</div>
		<?php if(!empty($this->quickLinks) || !empty($this->operation)): ?>
		<div id="rightBar">
			<?php if(isset($this->quickLinks->prec) && $this->quickLinks->prec != FALSE): ?>
				<a class="quickLinks" href="<?php echo $this->quickLinks->prec->href; ?>" title="<?php echo $this->quickLinks->prec->title; ?>">&laquo;</a>
			<?php elseif(isset($this->quickLinks->prec) && $this->quickLinks->prec == FALSE): ?>
				<a class="quickLinksDisabled" title="Disabilitato">&laquo;</a>
			<?php endif; ?>
			<?php if(!empty($this->operation)): ?>
				<a title="Mostra menu" id="click-menu"><span>M</span><span>E</span><span>N</span><span>U</span></a>
			<?php endif; ?>
			<?php if(isset($this->quickLinks->succ) && $this->quickLinks->succ != FALSE): ?>
				<a class="quickLinks" href="<?php echo $this->quickLinks->succ->href; ?>" title="<?php echo $this->quickLinks->succ->title; ?>">&raquo;</a>
			<?php elseif(isset($this->quickLinks->succ) && $this->quickLinks->succ == FALSE): ?>
				<a class="quickLinksDisabled" title="Disabilitato">&raquo;</a>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php if(!empty($this->operation)): ?>
			<div id="menu"><?php echo $this->operation; ?></div>
		<?php endif; ?>
		<script src="<?php echo JSURL . (LOCAL ? 'jquery/jquery.js' : 'jquery/jquery.min.js'); ?>" type="text/javascript"></script>
		<script type="text/javascript">
		// <![CDATA[
		if(jQuery.browser.msie && jQuery.browser.version<7)window.location="error_docs/not_supported.html";
		// ]]>
		</script>
		<?php if(!empty($this->js)): ?>
		<?php foreach($this->js as $key => $val): ?>
		<?php if(is_array($val)): ?>
		<?php foreach($val as $key2=>$val2): ?>
		<?php $appo = explode('|',$val2); ?>
		<?php if(isset($appo[1])): ?>
		<!--[if IE]><script src="<?php echo JSURL . $key . '/' . $appo[1] . (LOCAL ? '' : '.min') . '.js'; ?>" type="text/javascript"></script><![endif]-->
		<?php else: ?>
		<script src="<?php echo JSURL . $key . '/' . $val2 . (LOCAL ? '' : '.min') . '.js'; ?>" type="text/javascript"></script>
		<?php endif; ?>
		<?php endforeach; ?>
		<?php else: ?>
		<script src="<?php echo JSURL . $key . '/' . $val . (LOCAL ? '' : '.min') . '.js'; ?>" type="text/javascript"></script>
		<?php endif; ?>
		<?php endforeach; ?>
		<?php endif; ?>
		<?php if(file_exists(JSDIR . 'pages/' . $this->p . (LOCAL ? '' : '.min') . '.js')): ?>
			<script src="<?php echo JSURL . 'pages/' . $this->p . (LOCAL ? '' : '.min') . '.js'; ?>" type="text/javascript"></script>
		<?php endif; ?>
		<?php if((substr($_SERVER['REMOTE_ADDR'],0,7) != '192.168' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') || $_SERVER['SERVER_NAME'] != 'localhost' && !DEVELOP ): ?>
		<script src="<?php echo JSURL . 'custom/googleAnalytics.min.js'; ?>" type="text/javascript"></script>
		<?php endif; ?>
	</body>
</html>
