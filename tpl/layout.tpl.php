<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>FantaManajer</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<meta name="verify-v1" content="CkLFVD0+jN20Tcmm4kHQmzRijDZbny9QgKZcxkLaCl8=" />
		<meta name="description" content="Fantamanajer: un semplice tool per gestire il fantacalcio" />
		<meta name="author" content="Stefano Sonzogni"/>
		<meta name="keywords" content="fantacalcio,fantamanajer,fantamanger,manageriale fantacalcio,alzano sopra,condominio i pini,bergamo,calcio,piazzetta" />
		<link href="<?php echo CSSURL . 'screen.css' ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo CSSURL . 'style.css' ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo CSSURL . 'lightbox.css' ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo CSSURL . 'tabs.css' ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo CSSURL . 'print.css' ?>" media="print" rel="stylesheet" type="text/css" />
		<!--[if IE]><link rel="stylesheet" href="<?php echo CSSURL.'ie.css';?>" type="text/css" media="screen, projection"><![endif]-->
		<link href="<?php echo IMGSURL . 'favicon.ico' ?>" rel="shortcut icon" type="image/x-icon" />
		<script src="<?php echo JSURL . 'jquery/jquery.js' ?>" language="javascript" type="text/javascript"></script>
		<script src="<?php echo JSURL . 'jquery/jquery.dimension.js' ?>" language="javascript" type="text/javascript"></script>
		<script src="<?php echo JSURL . 'lightbox/lightbox.js' ?>" language="javascript" type="text/javascript"></script>
		<script src="<?php echo JSURL . 'ui/ui.base.js' ?>" language="javascript" type="text/javascript"></script>
		<script src="<?php echo JSURL . 'ui/ui.accordion.js' ?>" language="javascript" type="text/javascript"></script>
		<script src="<?php echo JSURL . 'ui/ui.tabs.js' ?>" language="javascript" type="text/javascript"></script>
		<script src="<?php echo JSURL . 'ui/ui.tabs.ext.js' ?>" language="javascript" type="text/javascript"></script>
		<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
		<script type="text/javascript">_uacct = "UA-3016148-1";urchinTracker();</script>
	</head>
	<body>
	<!--[if lt IE 7]>
		<div id="outer" style="border-left:960px solid #272727;float:left;">
		<div id="inner" style="margin-left:-960px;height:1px;position:relative;"><![endif]-->
		<div id="header" class="column last">
			<?php echo $this->header; ?>
		</div>
		<?php require('login.tpl.php'); ?>
		<div style="height:70px;">&nbsp;</div>
		<div id="navbar" class="column last">
			<?php echo $this->navbar ?>
		</div>
		<div id="content" class="column last">
			<div id="content-top-sx" class="column last">
				<div id="content-top-dx" class="column last">
					<div id="content-bottom-sx" class="column last">
						<div id="content-bottom-dx" class="column last">
							<div id="content-container" class="column last" >
								<?php echo $this->content ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<?php echo $this->footer ?>
		</div>
		<!--[if lt IE 7]>
		</div>
		</div>
		<![endif]-->
	</body>
</html>
