<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>FantaManajer<?php if(isset($this->pages['title'])) echo " - ".$this->pages['title']; ?></title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<meta name="verify-v1" content="CkLFVD0+jN20Tcmm4kHQmzRijDZbny9QgKZcxkLaCl8=" />
		<meta name="description" content="Fantamanajer: un semplice tool online scritto in php che ti permette di gestire al meglio il tuo torneo di fantacalcio." />
		<meta name="author" content="Stefano Sonzogni"/>
		<meta name="keywords" content="fantacalcio,fantamanajer,fantamanger,manageriale fantacalcio,alzano sopra,condominio i pini,bergamo,calcio,piazzetta" />
		<meta name="robots" content="index,follow" />
		<?php foreach($this->pages['css'] as $key => $val): ?>
			<link href="<?php echo CSSURL.$val.'.css';?>" media="screen" rel="stylesheet" type="text/css" />
		<?php endforeach; ?>
		<link href="<?php echo CSSURL . 'print.css' ?>" media="print" rel="stylesheet" type="text/css" />
		<!--[if IE]><link rel="stylesheet" href="<?php echo CSSURL.'ie.css';?>" type="text/css" media="screen, projection"><![endif]-->
		<link href="<?php echo IMGSURL . 'favicon.ico' ?>" rel="shortcut icon" type="image/x-icon" />
		<link rel="alternate" type="application/rss+xml" title="FantaManajer - RSS" href="<?php echo FULLURL.'rss.php'; ?>" />
		<link rel="alternate" href="<?php echo FULLURL.'rssPicLens.php'; ?>" type="application/rss+xml" title="Squadre" id="gallery" />
		<?php if(!empty($this->pages['js'])): ?>
		<?php foreach($this->pages['js'] as $key => $val): ?>
		<?php if(is_array($val)): ?>
		<?php foreach($val as $key2=>$val2): ?>
		<?php $appo = explode('|',$val2); ?>
		<?php if(isset($appo[1])): ?>
		<!--[if IE]><script src="<?php echo JSURL . $key . '/' . $appo[1] . '.js' ?>" language="javascript" type="text/javascript"></script><![endif]-->
		<?php else: ?>
		<script src="<?php echo JSURL . $key . '/' . $val2 . '.js' ?>" language="javascript" type="text/javascript"></script>
		<?php endif; ?>
		<?php endforeach; ?>
		<?php else: ?>
		<script src="<?php echo JSURL . $key . '/' . $val . '.js' ?>" language="javascript" type="text/javascript"></script>
		<?php endif; ?>
		<?php endforeach; ?>
		<?php endif; ?>
	</head>
	<?php flush(); ?>
	<body>
		<div id="big-container">
		<div id="header" class="column last">
			<?php echo $this->header; ?>
		</div>
		<?php require('login.tpl.php'); ?>
		<div id="fix" style="height:70px;">&nbsp;</div>
		<div id="navbar" class="column last">
			<?php echo $this->navbar ?>
		</div>
		<div id="content" class="column last">
			<div id="content-container" class="column last" >
				<?php echo $this->content ?>
			</div>
		</div>
		<div id="footer">
			<?php echo $this->footer ?>
		</div>
		</div>
		<div id="click-menu"></div>
		<div id="menu"><?php echo $this->operation ?></div>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#click-menu").mouseover(function(event){
					var appo = $("#menu");
						$("#menu").animate({right:'0px'},'slow');
				});
				$("#menu").mouseleave(function(event){
					var appo = $("#menu");
						$("#menu").animate({right:'-300px'},'slow');
				});
			});
		</script>
		<?php if( substr($_SERVER['REMOTE_ADDR'],0,7) != '192.168' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1' ): ?>
		<script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
			try {
				var pageTracker = _gat._getTracker("UA-3016148-1");
				pageTracker._trackPageview();
			} catch(err) {}
		</script>
		<?php endif; ?>
	</body>
</html>
