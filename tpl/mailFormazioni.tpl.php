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
				width:610px;
				float:left;
				padding: 0 0 15px 15px;
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
	<body>
		<?php $i = 0; ?>
		<div id="content" class="column last">
			<div id="content-top-sx" class="column last">
				<div id="content-top-dx" class="column last">
					<div id="content-bottom-sx" class="column last">
						<div id="content-bottom-dx" class="column last">
							<div id="content-container" class="column last" >
							<?php foreach ($this->titolari as $squadra => $formazione): ?>
								<?php if($i % 2 == 0): ?>
									<div class="riga column last">
								<?php endif; ?>
								<div id="squadradett" class="column last">
								<div class="box2-top-sx column last">
								<div class="box2-top-dx column last">
								<div class="box2-bottom-sx column last">
								<div class="box2-bottom-dx column last">
								<div class="box-content column last">
								<?php if ($formazione != FALSE): ?>
									<h3><?php echo $this->squadre[$squadra]['nome']; ?></h3>
									<h4>Titolari</h4><hr />
									<table>
									<?php foreach($formazione as $key => $val): ?>
										<tr>
										<?php if($this->cap[$squadra]['C'] == $val['idGioc']) : ?>
											<td class="tableimg"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL.'player-cap2.png' ?>"/></td>
										<?php else: ?>
											<td class="tableimg"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL.'player-tit2.png' ?>"/></td>
										<?php endif; ?>
											<td><?php echo $val['cognome']; ?></td>
											<td><?php echo $val['nome']; ?></td>
											<td><?php if(array_search($val['idGioc'],$this->cap[$squadra]) != FALSE)  echo array_search($val['idGioc'],$this->cap[$squadra]); else echo '&nbsp;'; ?></td>
										</tr>
									<?php endforeach; ?>
									</table>
									<?php if($this->panchinari[$squadra] != FALSE): ?>
										<h4>Panchinari</h4><hr />
										<table>
										<?php foreach ($this->panchinari[$squadra] as $key => $val): ?>
											<tr>
												<td class="tableimg"><img alt="Panchinaro" title="Panchinaro" src="<?php echo IMGSURL.'player-panch2.png' ?>"/></td>
												<td><?php echo $val['cognome']; ?></td>
												<td><?php echo $val['nome']; ?></td>
											</tr>
										<?php endforeach; ?>
										</table>
									<?php endif; ?>
								<?php else: ?>
									<h3><?php echo $this->squadre[$squadra][1]; ?></h3>
									Non ha settato la formazione.
								<?php endif; ?>
								</div>
								</div>
								</div>
								</div>
								</div>
								</div>
								<?php $i++; ?>
								<?php if($i%2 == 0): ?>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
