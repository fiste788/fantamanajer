<div id="cardPlayer" class="column last">
	<img class="column" alt="<?php echo $this->dettaglioGioc['dettaglio']->cognome . ' ' . $this->dettaglioGioc['dettaglio']->nome; ?>" src="<?php echo $this->pathFoto; ?>"/>
	<div id="datiGioc" class="column last">
		<?php if(isset($_GET['edit']) && $_GET['edit'] == 'edit' && $_SESSION['roles'] == '2'): ?>
		<div id="formData">
			<input type="hidden" name="idGioc" value="<?php echo $this->dettaglioGioc['dettaglio']->idGioc; ?>" />
			<div>
				<label for="cognome">Cognome:</label>
				<input id="cognome" type="text" name="cognome" value="<?php if(isset($this->dettaglioGioc['dettaglio']->cognome)) echo $this->dettaglioGioc['dettaglio']->cognome; ?>" />
			</div>
			<div>
				<label for="nome">Nome:</label>
				<input id="nome" type="text" name="nome" value="<?php if(isset($this->dettaglioGioc['dettaglio']->nome)) echo $this->dettaglioGioc['dettaglio']->nome; ?>" />
			</div>
		</div>
		<?php else: ?>
			<h3><?php echo $this->dettaglioGioc['dettaglio']->cognome . ' ' . $this->dettaglioGioc['dettaglio']->nome; ?></h3>
		<?php endif; ?>
		<img title="<?php echo $this->dettaglioGioc['dettaglio']->nomeClub; ?>" class="shield" alt="<?php echo $this->dettaglioGioc['dettaglio']->nomeClub; ?>" src="<?php echo $this->pathClub; ?>"/>
		<p><?php echo $this->ruoli[$this->dettaglioGioc['dettaglio']->ruolo]; ?></p>
		<?php if($_SESSION['logged']): ?><p>Squadra: <?php echo $this->label; ?></p><?php endif; ?>
		<p>Presenze: <?php echo $this->dettaglioGioc['dettaglio']->presenze . " (" . $this->dettaglioGioc['dettaglio']->presenzeVoto . ")"; ?></p>
		<p>Gol: <?php if($this->dettaglioGioc['dettaglio']->ruolo != 'P') echo $this->dettaglioGioc['dettaglio']->gol; elseif($this->dettaglioGioc['dettaglio']->golSubiti== 0) echo $this->dettaglioGioc['dettaglio']->golSubiti; else echo "-".$this->dettaglioGioc['dettaglio']->golSubiti; ?></p>
		<p>Assist: <?php echo $this->dettaglioGioc['dettaglio']->assist; ?></p>
		<p>Media voti: <?php if(!empty($this->dettaglioGioc['dettaglio']->avgVoti)) echo $this->dettaglioGioc['dettaglio']->avgVoti; ?></p>
		<p>Media punti: <?php if(!empty($this->dettaglioGioc['dettaglio']->avgPunti)) echo $this->dettaglioGioc['dettaglio']->avgPunti; ?></p>
	</div>
	<?php if(isset($_GET['edit']) && $_GET['edit'] == 'edit' && $_SESSION['roles'] == '2'): ?>
	<div class="uploadleft column last">
		<input class="upload" type="file" name="userfile" id="upload"/>
	</div>
	<?php endif; ?>
</div>
<?php if(isset($this->dettaglioGioc['dettaglio']->data)): ?>
<table style="width:100px;clear:both;" class="column last" cellpadding="0" cellspacing="0">
	<tr>
		<th>Giornata</th>
	</tr>
	<tr>
		<td>Punti</td>
	</tr>
	<tr>
		<td>Voti</td>
	</tr>
	<tr>
		<td>Gol</td>
	</tr>
	<tr>
		<td>Assist</td>
	</tr>
	<tr>
		<td>Ammonizioni</td>
	</tr>
	<tr>
		<td>Espulsioni</td>
	</tr>
</table>
<div id="tabGiocatore" class="column last">
	<table class="column last" cellpadding="0" cellspacing="0" style="width:<?php echo count($this->dettaglioGioc['dettaglio']->data) * 40; ?>px;margin:0;">
		<tr>
		<?php foreach($this->dettaglioGioc['dettaglio']->data as $key => $val): ?>
			<th><?php echo $key; ?></th>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['dettaglio']->data as $key => $val): ?>
			<td><?php echo $val->punti; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['dettaglio']->data as $key => $val): ?>
			<td><?php if($val->voto != '0') echo $val->voto; else echo "&nbsp;"; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['dettaglio']->data as $key => $val): ?>
			<td><?php if($this->dettaglioGioc['dettaglio']->ruolo!="P") echo $val->gol; elseif ($val->golSub == 0) echo $val->golSub; else echo "-".$val->golSub ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['dettaglio']->data as $key => $val): ?>
			<td><?php echo $val->assist; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['dettaglio']->data as $key => $val): ?>
			<td><?php echo $val->ammonizioni; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['dettaglio']->data as $key => $val): ?>
			<td><?php echo $val->espulsioni; ?></td>
		<?php endforeach; ?>
		</tr>
	</table>
</div>
<div id="grafico">
	<div id="placeholder" class="column last" style="width:950px;height:300px;clear:both;overflow:hidden;">&nbsp;</div>
	<div id="overview" class="column " style="width:200px;height:100px;clear:both;cursor:pointer;">&nbsp;</div>
	<p class="column" style="width:720px;">Seleziona sulla miniatura una parte di grafico per ingrandirla. Per questa funzionalità si consiglia di usare browser come Safari, Firefox o Opera invece di altri meno performanti come Internet Explorer</p><p class="column" id="selection">&nbsp;</p>
	<div id="hidden" class="hidden">&nbsp;</div>
	<a id="clearSelection" class="column hidden">(Cancella selezione)</a>
</div>
<script id="source" type="text/javascript">
// <![CDATA[
		var datasets = {
			"voto":
			{
			label: "Voto <?php echo $this->dettaglioGioc['dettaglio']->cognome ." ". $this->dettaglioGioc['dettaglio']->nome; ?>",
			data: [<?php $i = 0; foreach($this->dettaglioGioc['dettaglio']->data as $key => $val): $i++; ?><?php if($val->punti != '0') echo '[' . $key . ',' . $val->punti . ']'; if($val->punti != '0' && count($this->dettaglioGioc['dettaglio']->data) != $i) echo ','; endforeach; ?>]
			},"punti":
			{
			label: "Punteggio <?php echo $this->dettaglioGioc['dettaglio']->cognome ." ". $this->dettaglioGioc['dettaglio']->nome; ?>",
			data: [<?php $i = 0; foreach($this->dettaglioGioc['dettaglio']->data as $key => $val): $i++; ?><?php if($val->voto != '0') echo '[' . $key . ',' . $val->voto . ']'; if($val->voto != '0' && count($this->dettaglioGioc['dettaglio']->data) != $i) echo ','; endforeach; ?>]
			}
		};
// ]]>
</script>
<?php endif; ?>
