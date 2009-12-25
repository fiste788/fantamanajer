<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>FantaManajer</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
					<style type="text/css">	html,body,div,span,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,code,del,dfn,em,img,q,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td {
			border:0;
			font-weight:inherit;
			font-style:inherit;
			font-size:12px;
			font-family:sans-serif;
			vertical-align:baseline;
			margin:0;
			padding:0;
			}

			body {
			line-height:1.5;
			background:#272727;
			font-size:75%;
			color:#fff;
			font-family:sans-serif;
			margin: 0;
			text-align:center;
			padding: 10px 11px;
			display:block;
			}
				
			table {
				border-collapse:separate;
				border-spacing:0;
				margin-bottom:1.4em;
				width:100%;
				clear:both;
			}
				
			tbody {
				width:100%;
				color:#fff;
			}
			
			th, td {
				padding:4px 10px 4px 0pt;
			}
			
			caption,th,td {
				text-align:left;
				font-weight:400;
			}
			
			h1,h2,h3,h4,h5,h6 {
				color:#ff9900;
				font-family:"Helvetica Neue", Helvetica, Arial, sans-serif;
				font-weight:400;
			}
			
			th {
				border-bottom:2px solid #ccc;
				font-weight:700;
			}
				
			td {
				border-bottom:1px solid #ddd;
			}
			
			.column {
				float:left;
				margin-right:10px;
			}
				
			.last {
				margin-right:0;
			}
			
			a, a:link, a:visited, a:hover {
				color: #ff9900;
				text-decoration:none;
				font-weight:bold;
				cursor:pointer;
			}
			
			#content {
				background:#383838;
				width:840px;
				text-align:justify;
				color:#fff;
				clear:both;
			}

			#content #content-top-sx {
				background:transparent url(<?php echo IMGSURL; ?>content-top-sx.png) no-repeat scroll left top;
			}

			#content #content-top-dx {
				background:transparent url(<?php echo IMGSURL; ?>content-top-dx.png) no-repeat scroll right top;
			}

			#content #content-bottom-sx {
				background:transparent url(<?php echo IMGSURL; ?>content-bottom-sx.png) no-repeat scroll left bottom;
			}

			#content #content-bottom-dx {
				background:transparent url(<?php echo IMGSURL; ?>content-bottom-dx.png) no-repeat scroll right bottom;
	
			}

			#content #content-top-sx, #content #content-top-dx,#content #content-bottom-sx,#content #content-bottom-dx {
				width:100%;
			}

			#content-container {
				width:100%;
			}
			
			.tableimg img {
				margin: 0px 3px -4px;
			}

			.tableimg {
				width:20px;
			}
				
			.main-content {
				clear:both;
				float:left;
				padding: 15px;
			}
			
			h3 {
				font-weight:bold;
				font-size:16px;
			}
			
			h4 {
				font-weight:bold;
				font-size:13px;
			}
			
			.box2-top-sx {background: #514e46 url(<?php echo IMGSURL; ?>box2-top-sx.png) no-repeat top left;}

			.box2-top-dx {background: transparent url(<?php echo IMGSURL; ?>box2-top-dx.png) no-repeat top right;}

			.box2-bottom-sx {background: transparent url(<?php echo IMGSURL; ?>box2-bottom-sx.png) no-repeat bottom left;}

			.box2-bottom-dx {background: transparent url(<?php echo IMGSURL; ?>box2-bottom-dx.png) no-repeat bottom right;}


			.box-content {
				padding:10px;
				width:380px;
				text-align:center;
			}
			
			#squadradett {
				float:left;
				width:400px;
				margin:10px;
			}
			
			.riga {
				margin: 0;
				clear:both;
				width:840px;
			}
		</style>
	</head>
	<body style="border:0;	font-weight:inherit;font-style:inherit;font-size:12px;font-family:sans-serif;vertical-align:baseline;margin:0;padding:0;line-height:1.5;background:#272727;font-size:75%;	color:#fff;font-family:sans-serif;margin: 0;text-align:center;padding: 10px 11px;display:block;">
		<a style="float:left;margin:0;color: #ff9900;text-decoration:none;	font-weight:bold;cursor:pointer;" class="linkheader column last" title="Home" href="http://www.fantamanajer.it/home.html">
			<img alt="Header-logo" src="<?php echo IMGSURL.'header-logo.png'; ?>" />
		</a>
		<div style="float:left;background:#383838;width:840px;text-align:justify;color:#fff;clear:both;margin:0" id="content" class="column last">
			<div style="float:left;margin:0;background:transparent url(<?php echo IMGSURL; ?>content-top-sx.png) no-repeat scroll left top;width:100%;" id="content-top-sx" class="column last">
				<div style="float:left;margin:0;background:transparent url(<?php echo IMGSURL; ?>content-top-dx.png) no-repeat scroll right top;width:100%;" id="content-top-dx" class="column last">
					<div style="float:left;margin:0;background:transparent url(<?php echo IMGSURL; ?>content-bottom-sx.png) no-repeat scroll left bottom;width:0;" id="content-bottom-sx" class="column last">
						<div style="float:left;margin:0;background:transparent url(<?php echo IMGSURL; ?>content-bottom-dx.png) no-repeat scroll right bottom;width:100%;" id="content-bottom-dx" class="column last">
							<div style="float:left;margin:0;width:100%;" id="content-container" class="column last">
								<div style="clear:both;float:left;padding: 15px;" class="main-content">
									<h3 style="color:#ff9900;font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;font-weight:400;">Benvenuto nel FantaManajer</h3>
									<p>FantaManajer è un sito che ti permette di gestire la tua lega del fantacalcio creata con i tuoi amici<br />
									Dal sito è possibile settare la tua formazione e si occuperà lui di scaricare i punteggi e calcolare il tuo voto!</p><br />
									<div>
										Sei stato iscritto dall'amministratore della lega: <strong><?php echo $this->lega->nomeLega; ?></strong><br />
										Ecco quì i dati per accedere.<br />
										Username: <strong><?php echo $this->username; ?></strong><br />
										Password: <strong><?php echo $this->password; ?></strong><br />
										La tua squadra si chiama <?php echo $this->squadra; ?>. Una volta effettuato l'accesso potrai cambiare il nome della tua squadra e modificare i tuoi dati personali.<br /><br />
										Clicca <a style="color: #ff9900;text-decoration:none;font-weight:bold;	cursor:pointer;" href="http://www.fantamanajer.it">quì</a> per accedere al sito.<br />
									</div>
									<p style="float:left;margin:0;" class="column last">Si prega di non rispondere a questa mail in quanto non verrà presa in considerazione.<br /> 
									Per domande o chiarimenti contatta <?php if($this->autore->amministratore != '2'): ?>l'amministratore di lega all'indirizzo <a style="color: #ff9900;text-decoration:none;	font-weight:bold;cursor:pointer;" href="mailto:<?php echo $this->autore->mail; ?>"><?php echo $this->autore->mail; ?></a> o <?php endif; ?>gli amministratori all'indirizzo <a style="color: #ff9900;text-decoration:none;font-weight:bold;cursor:pointer;" href="mailto:admin@fantamanajer.it">admin@fantamanajer.it</a></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
