<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>FantaManajer<?php if(isset($this->title)) echo " - " . $this->title; ?></title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<meta name="verify-v1" content="CkLFVD0+jN20Tcmm4kHQmzRijDZbny9QgKZcxkLaCl8=" />
		<meta name="description" content="Fantamanajer: un semplice tool online scritto in php che ti permette di gestire al meglio il tuo torneo di fantacalcio." />
		<meta name="author" content="Stefano Sonzogni"/>
		<meta name="keywords" content="fantacalcio,fantamanajer,fantamanger,manageriale fantacalcio,alzano sopra,condominio i pini,bergamo,calcio,piazzetta" />
		<meta name="robots" content="index,follow" />
		<link href="<?php echo CSSURL . 'screen.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
		<link href="<?php echo CSSURL . 'print.css' ?>" media="print" rel="stylesheet" type="text/css" />
		<!--[if IE]><link rel="stylesheet" href="<?php echo CSSURL . 'ie.css';?>" type="text/css" media="screen, projection"><![endif]-->
		<link href="<?php echo IMGSURL . 'favicon.ico' ?>" rel="shortcut icon" type="image/x-icon" />
		<link rel="alternate" type="application/rss+xml" title="FantaManajer - RSS" href="<?php echo FULLURL . 'rss.php'; ?>" />
		<link rel="alternate" href="<?php echo FULLURL . 'rssPicLens.php'; ?>" type="application/rss+xml" title="Squadre" id="gallery" />
		<script src="<?php echo JSURL . 'jquery/jquery.js' ?>" language="javascript" type="text/javascript"></script>
		<?php if(!empty($this->js)): ?>
		<?php foreach($this->js as $key => $val): ?>
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
		<div id="navbar" class="column last">
			<?php echo $this->navbar ?>
		</div>
		<div id="content" class="column last">
			<?php if(isset($this->message)): ?>
			<?php switch($this->message['level']): 
				 case 0: ?>
				<div id="messaggio" title="Clicca per nascondere" class="messaggio good column last">
					<img alt="OK" src="<?php echo IMGSURL . 'ok-big.png'; ?>" />
				<?php break; case 1: ?>
				<div id="messaggio" title="Clicca per nascondere" class="messaggio bad column last">
					<img alt="!" src="<?php echo IMGSURL . 'attention-bad-big.png'; ?>" title="Attenzione!" />
				<?php break; case 2: ?>
				<div id="messaggio" title="Clicca per nascondere" class="messaggio neut column last">
					<img alt="!" src="<?php echo IMGSURL . 'attention-big.png'; ?>" title="Attenzione!" />
				<?php endswitch; ?>
					<span><?php echo $this->message['text']; ?></span>
				</div>
				<script type="text/javascript">
					$(document).ready(function(){
						$("#messaggio").effect("pulsate", { times: 2 }, 1000, function(){
							$("#messaggio").hover(function () {
								$(this).fadeTo("fast",0.2);
							},function () {
								$(this).fadeTo("fast",1);
							});
						});
						$("#messaggio").click(function () {
							$("div#messaggio").fadeOut("slow");
						});
					});
				</script>
			<?php endif; ?>
			<div id="content-container" class="column last" >
				<?php echo $this->content ?>
			</div>
		</div>
		<div id="footer">
			<?php echo $this->footer ?>
		</div>
		</div>
		<div id="rightBar">
			<?php if(isset($this->quickLinks['prec']) && $this->quickLinks['prec'] != FALSE): ?>
				<a class="quickLinks" href="<?php echo $this->quickLinks['prec']['href']; ?>" title="<?php echo $this->quickLinks['prec']['title']; ?>">&laquo;</a>
			<?php elseif(isset($this->quickLinks['prec']) && $this->quickLinks['prec'] == FALSE): ?>
				<a class="quickLinksDisabled" title="Disabilitato">&laquo;</a>
			<?php endif; ?>
			<?php if(!empty($this->operation)): ?>
				<a title="Mostra menu" id="click-menu"><span>M</span><span>E</span><span>N</span><span>U</span></a>
			<?php endif; ?>
			<?php if(isset($this->quickLinks['succ']) && $this->quickLinks['succ'] != FALSE): ?>
				<a class="quickLinks" href="<?php echo $this->quickLinks['succ']['href']; ?>" title="<?php echo $this->quickLinks['succ']['title']; ?>">&raquo;</a>
			<?php elseif(isset($this->quickLinks['succ']) && $this->quickLinks['succ'] == FALSE): ?>
				<a class="quickLinksDisabled" title="Disabilitato">&raquo;</a>
			<?php endif; ?>
		</div>
		<?php if(!empty($this->operation)): ?>
			<div id="menu"><?php echo $this->operation ?></div>
			<script type="text/javascript">
				$(document).ready(function(){
					$("#click-menu").toggle(function(event){
							$("#menu").animate({right:'0px'},'slow');
							$("#click-menu").attr("title","Nascondi menu");
					},
					function(event){
							$("#menu").animate({right:'-300px'},'slow');
							$("#click-menu").attr("title","Mostra menu");
					});
				});
			</script>
			<?php endif; ?>
			<?php if( substr($_SERVER['REMOTE_ADDR'],0,7) != '192.168' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1' && !DEVELOP ): ?>
			<script type="text/javascript">
				var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
				document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
			</script>
			<script type="text/javascript">
				try {
					var pageTracker = _gat._getTracker("UA-3016148-1");
					pageTracker._setDomainName("www.fantamanajer.it");
					pageTracker._trackPageview();
				} catch(err) {}
			</script>
		<?php endif; ?>
	</body>
</html>
