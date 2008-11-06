<?php ?>
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
			min-width:960px;
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
				width:740px;
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
			
			.rosso {
				background:transparent url(<?php echo IMGSURL; ?>bg-player-exit.png) repeat-x scroll left top;
				text-align:center;
			}
			
			.verde {
				background:transparent url(<?php echo IMGSURL; ?>bg-player-in.png) repeat-x scroll left top;
				text-align:center;
			}
			
			#punteggidett .nome , #punteggidett .cognome{
				width:200px;
			}
			
			#punteggidett .ruolo , #punteggidett .punt, #punteggidett .club{
				width:50px;
			}		
			
			h4 {
				float:left;
				font-size:18px;
				font-weight:normal;
				text-align:center;
				margin:0 0 10px 0;
				width:33%;
			}
			
			#classifica h4 {
				width:100%;
			}
			
			
			#punteggidett caption{
				font-weight:bold;
				background-color: transparent;
				color:#ff9900;
				font-size: 14px;
			}	
			
			.tableimg img {
				margin: 0px 3px -4px;
			}
			
			.box2-top-sx {background: #514e46 url(<?php echo IMGSURL; ?>box2-top-sx.png) no-repeat top left;}

			.box2-top-dx {background: transparent url(<?php echo IMGSURL; ?>box2-top-dx.png) no-repeat top right;}

			.box2-bottom-sx {background: transparent url(<?php echo IMGSURL; ?>box2-bottom-sx.png) no-repeat bottom left;}

			.box2-bottom-dx {background: transparent url(<?php echo IMGSURL; ?>box2-bottom-dx.png) no-repeat bottom right;}

			.box-content {
				padding:10px;
				width:530px;
				text-align:center;
			}
			
			#punteggidett {
				float:left;
				width:550px;
				margin:10px 95px;
			}
			
			#classifica {
				float:left;
				width:350px;
				margin:10px 195px;
			}
			
			#classifica .box-content {
				width:330px;
			}
		</style>
	</head>
	<body>
		<div id="content" class="column last">
		<div id="content-top-sx" class="column last">
		<div id="content-top-dx" class="column last">
		<div id="content-bottom-sx" class="column last">
		<div id="content-bottom-dx" class="column last">
		<div id="content-container" class="column last" >
			<div id="punteggidett" class="column last">
			<div class="box2-top-sx column last">
			<div class="box2-top-dx column last">
			<div class="box2-bottom-sx column last">
			<div class="box2-bottom-dx column last">
			<div class="box-content column last">
			<h4><?php echo $this->squadra; ?></h4>
			<h4>Punteggio: <?php echo $this->somma; ?></h4>
			<h4>Giornata: <?php echo $this->giornata; ?></h4>
			<table class="column last">
				<caption>Titolari</caption>
				<tbody>
					<tr>
						<th>&nbsp;</th>
						<th class="cognome">Cognome</th>
						<th class="nome">Nome</th>
						<th class="ruolo">Ruolo</th>
						<th class="club">Club</th>
						<th class="punt">Punt.</th>
					</tr>
					<?php $panch=$this->formazione;$tito=array_splice($panch,0,11);?>
            <?php foreach($tito as $key=>$val): ?>
					<?php if($val['Considerato'] == 0 or ($val['Voto']=="" and $val['Considerato']>0)): ?>
						<tr class="rosso">
							<td class="tableimg"><img alt="Sostituito" title="Sostituito" src="<?php echo IMGSURL.'player-sost.png' ?>"/></td>
					<?php elseif($val['Considerato'] == 2): ?>
						<tr>
							<td class="tableimg"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL.'player-cap2.png' ?>"/></td>
					<?php $val['Voto']*=2; else: ?>
						<tr>
							<td class="tableimg"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL.'player-tit2.png' ?>"/></td>
					<?php endif; ?>		
							<td><?php echo $val['Cognome']; ?></td>
							<td><?php echo $val['Nome']; if($val['Considerato'] ==2) echo '<span id="cap">(C)</span>'; ?></td>
							<td><?php echo $val['Ruolo']; ?></td>
							<td><?php echo $val['Club']; ?></td>
							<td><?php if($val['Considerato'] > 0) echo $val['Voto']; else echo "&nbsp;"; ?></td>
						</tr>
			<?php endforeach; ?>
				</tbody>
			</table>

			<table class="column last">
				<caption>Panchinari</caption>
				<tbody>
					<tr>
						<th>&nbsp;</th>
						<th class="cognome">Cognome</th>
						<th class="nome">Nome</th>
						<th class="ruolo">Ruolo</th>
						<th class="club">Club</th>
						<th class="punt">Punt.</th>
					</tr>
					<?php foreach($panch as $key=>$val): ?>
					<?php if($val['Considerato'] == 1): ?>
						<tr class="verde">
							<td class="tableimg"><img alt="Sostituito" title="Sostituito" src="<?php echo IMGSURL.'player-sost-in.png' ?>"/></td>
					<?php elseif($val['Considerato']==2): ?>
						<tr>
							<td class="tableimg"><img alt="Titolare" title="Titolare" src="<?php echo IMGSURL.'player-cap.png' ?>"/></td>
					<?php else: ?>
						<tr>
							<td class="tableimg"><img alt="Panchinaro" title="Panchinaro" src="<?php echo IMGSURL.'player-panch2.png' ?>"/></td>
					<?php endif; ?>
							<td><?php echo $val['Cognome']; ?></td>
							<td><?php echo $val['Nome']; ?></td>
							<td><?php echo $val['Ruolo']; ?></td>
							<td><?php echo $val['Club']; ?></td>
							<td><?php if($val['Considerato'] > 0) echo $val['Voto']; else echo "&nbsp;"; ?></td>
						</tr>
			<?php endforeach; ?>
				</tbody>
			</table>
			</div>
			</div>
			</div>
			</div>
			</div>
			</div>
			<?php if(isset($this->classifica)): ?>
			<div id="classifica" class="column last">
			<div class="box2-top-sx column last">
			<div class="box2-top-dx column last">
			<div class="box2-bottom-sx column last">
			<div class="box2-bottom-dx column last">
			<div class="box-content column last">
				<h4>Classifica</h4>
				<table id="classifica-home" cellpadding="0" cellspacing="0">
					<tbody>
						<tr>
							<th>Squadra</th>
							<th>P.ti</th>
						</tr>
						<?php $i=0; ?>
						<?php foreach ($this->classifica as $key=>$val): ?>
							<tr <?php if($this->differenza[$i] < 0): ?>
									<?php echo 'class="rosso" title="' . $this->differenza[$i]. ' Pos."'; ?>
								<?php elseif($this->differenza[$i] > 0): ?>
									<?php echo 'class="verde" title="+ ' . $this->differenza[$i]. ' Pos."'; ?>
								<?php endif; ?>>
								<td><?php echo $this->squadre[$key][1]; ?></td>
								<td><?php echo $val; ?></td>
							</tr>
						<?php $i++; endforeach; ?>
					</tbody>
				</table>
			</div>
			</div>
			</div>
			</div>
			</div>
			</div>
			<?php endif; ?>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
	</body>
</html>
