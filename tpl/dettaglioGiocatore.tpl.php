<div id="cardPlayer" class="column last">
	<?php if(isset($_GET['edit']) && $_GET['edit'] == 'edit' && $_SESSION['roles'] == '2'): ?>
		<div id="formData">
			<input type="hidden" name="idGioc" value="<?php echo $this->dettaglioGioc['dettaglio']->id; ?>" />
			<div>
				<label for="cognome">Cognome:</label>
				<input id="cognome" type="text" name="cognome" value="<?php echo (isset($this->dettaglioGioc['dettaglio']->cognome)) ? $this->dettaglioGioc['dettaglio']->cognome : ''; ?>" />
			</div>
			<div>
				<label for="nome">Nome:</label>
				<input id="nome" type="text" name="nome" value="<?php echo (isset($this->dettaglioGioc['dettaglio']->nome)) ? $this->dettaglioGioc['dettaglio']->nome : ''; ?>" />
			</div>
		</div>
	<?php else: ?>
		<h3><?php echo $this->dettaglioGioc['dettaglio']->cognome . ' ' . $this->dettaglioGioc['dettaglio']->nome; ?></h3>
	<?php endif; ?>
	<div id="datiGioc" class="column last">
		<img class="column" alt="<?php echo $this->dettaglioGioc['dettaglio']->cognome . ' ' . $this->dettaglioGioc['dettaglio']->nome; ?>" src="<?php echo $this->pathFoto; ?>" />
		<div class="column">
			<p><?php echo $this->ruoli[$this->dettaglioGioc['dettaglio']->ruolo]; ?></p>
			<?php if($_SESSION['logged']): ?><p>Squadra: <?php echo $this->label; ?></p><?php endif; ?>
			<p>Presenze: <?php echo $this->dettaglioGioc['dettaglio']->presenze . " (" . $this->dettaglioGioc['dettaglio']->presenzeVoto . ")"; ?></p>
			<p>Gol: <?php if($this->dettaglioGioc['dettaglio']->ruolo != 'P') echo $this->dettaglioGioc['dettaglio']->gol;elseif($this->dettaglioGioc['dettaglio']->golSubiti == 0) echo $this->dettaglioGioc['dettaglio']->golSubiti;else "-" . $this->dettaglioGioc['dettaglio']->golSubiti; ?></p>
			<p>Assist: <?php echo $this->dettaglioGioc['dettaglio']->assist; ?></p>
			<p>Media voti: <?php echo (!empty($this->dettaglioGioc['dettaglio']->avgVoti)) ? $this->dettaglioGioc['dettaglio']->avgVoti : ''; ?></p>
			<p>Media punti: <?php echo (!empty($this->dettaglioGioc['dettaglio']->avgPunti)) ? $this->dettaglioGioc['dettaglio']->avgPunti : ''; ?></p>
		</div>
		<?php if($this->dettaglioGioc['dettaglio']->nomeClub != NULL): ?>        
			<a target="_blank" href="<?php echo Links::getLink('dettaglioClub',array('club'=>$this->dettaglioGioc['dettaglio']->idClub)); ?>">
				<img height="50%" width="60" title="<?php echo $this->dettaglioGioc['dettaglio']->nomeClub; ?>" class="shield" alt="<?php echo $this->dettaglioGioc['dettaglio']->nomeClub; ?>" src="<?php echo $this->pathClub; ?>"/>
			</a>
		<?php endif; ?>
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
			<td><?php echo ($val->voto != '0') ? $val->voto : "&nbsp;"; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->dettaglioGioc['dettaglio']->data as $key => $val): ?>
			<td><?php if($this->dettaglioGioc['dettaglio']->ruolo != "P") echo $val->gol;elseif($val->golSub == 0) echo $val->golSub;else echo "-" . $val->golSub ?></td>
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
			data: [<?php $i = 0; foreach($this->dettaglioGioc['dettaglio']->data as $key => $val): $i++; ?><?php echo ($val->punti != '0') ? '[' . $key . ',' . $val->punti . ']' : ''; echo ($val->punti != '0' && count($this->dettaglioGioc['dettaglio']->data) != $i) ? ',' : ''; endforeach; ?>]
			},"punti":
			{
			label: "Punteggio <?php echo $this->dettaglioGioc['dettaglio']->cognome ." ". $this->dettaglioGioc['dettaglio']->nome; ?>",
			data: [<?php $i = 0; foreach($this->dettaglioGioc['dettaglio']->data as $key => $val): $i++; ?><?php echo ($val->voto != '0') ? '[' . $key . ',' . $val->voto . ']' : ''; echo ($val->voto != '0' && count($this->dettaglioGioc['dettaglio']->data) != $i) ? ',' : ''; endforeach; ?>]
			}
		};
// ]]>
</script>
<?php endif; ?>
