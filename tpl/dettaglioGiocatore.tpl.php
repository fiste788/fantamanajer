<div id="cardPlayer">
	<?php if(isset($_GET['edit']) && $_GET['edit'] == 'edit' && $_SESSION['roles'] == '2'): ?>
		<div id="formData">
			<input type="hidden" name="idGioc" value="<?php echo $this->giocatore->id; ?>" />
			<div>
				<label for="cognome">Cognome:</label>
				<input id="cognome" type="text" name="cognome" value="<?php echo (isset($this->giocatore->cognome)) ? $this->giocatore->cognome : ''; ?>" />
			</div>
			<div>
				<label for="nome">Nome:</label>
				<input id="nome" type="text" name="nome" value="<?php echo (isset($this->giocatore->nome)) ? $this->giocatore->nome : ''; ?>" />
			</div>
		</div>
	<?php else: ?>
		<h3><?php echo $this->giocatore->cognome . ' ' . $this->giocatore->nome; ?></h3>
	<?php endif; ?>
	<div id="datiGioc">
		<img class="foto" alt="<?php echo $this->giocatore; ?>" src="<?php echo $this->pathFoto; ?>" />
		<div class="column">
			<p><?php echo $this->ruoli[$this->giocatore->ruolo]; ?></p>
			<?php if($_SESSION['logged']): ?><p>Squadra: <?php echo $this->label; ?></p><?php endif; ?>
			<p>Presenze: <?php echo $this->giocatore->presenze . " (" . $this->giocatore->presenzeVoto . ")"; ?></p>
			<p>Gol: <?php if($this->giocatore->ruolo != 'P') echo $this->giocatore->gol; elseif($this->giocatore->golSubiti == 0) echo $this->giocatore->golSubiti; else "-" . $this->giocatore->golSubiti; ?></p>
			<p>Assist: <?php echo $this->giocatore->assist; ?></p>
			<p>Media voti: <?php echo (!empty($this->giocatore->avgVoti)) ? $this->giocatore->avgVoti : ''; ?></p>
			<p>Media punti: <?php echo (!empty($this->giocatore->avgPunti)) ? $this->giocatore->avgPunti : ''; ?></p>
		</div>
		<?php if($this->giocatore->nomeClub != NULL): ?>
			<a class="right" href="<?php echo Links::getLink('dettaglioClub',array('club'=>$this->giocatore->idClub)); ?>">
				<img height="50%" width="60" title="<?php echo $this->giocatore->nomeClub; ?>" class="shield" alt="<?php echo $this->giocatore->nomeClub; ?>" src="<?php echo $this->pathClub; ?>"/>
			</a>
		<?php endif; ?>
	</div>
	<?php if(isset($_GET['edit']) && $_GET['edit'] == 'edit' && $_SESSION['roles'] == '2'): ?>
	<div class="uploadleft column last">
		<input class="upload" type="file" name="userfile" id="upload"/>
	</div>
	<?php endif; ?>
</div>
<?php if(isset($this->giocatore->voti) && !empty($this->giocatore->voti)): ?>
<table style="width:100px;clear:both;" class="table column">
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
<div id="tabGiocatore" class="column">
	<table class="column table" style="width:<?php echo count($this->giocatore->voti) * 40; ?>px;margin:0;">
		<tr>
		<?php foreach($this->giocatore->voti as $key => $val): ?>
			<th><?php echo $val->idGiornata; ?></th>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->giocatore->voti as $key => $val): ?>
			<td><?php echo $val->punti; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->giocatore->voti as $key => $val): ?>
			<td><?php echo ($val->voto != '0') ? $val->voto : "&nbsp;"; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->giocatore->voti as $key => $val): ?>
			<td><?php if($this->giocatore->ruolo != "P") echo $val->gol;elseif($val->golSubiti == 0) echo $val->golSubiti; else echo "-" . $val->golSubiti; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->giocatore->voti as $key => $val): ?>
			<td><?php echo $val->assist; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->giocatore->voti as $key => $val): ?>
			<td><?php echo $val->ammonizioni; ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach($this->giocatore->voti as $key => $val): ?>
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
			label: "Voto <?php echo $this->giocatore->cognome ." ". $this->giocatore->nome; ?>",
			data: [<?php $i = 0; foreach($this->giocatore->voti as $key => $val): $i++; ?><?php echo ($val->punti != '0') ? '[' . $key . ',' . $val->punti . ']' : ''; echo ($val->punti != '0' && count($this->giocatore->voti) != $i) ? ',' : ''; endforeach; ?>]
			},"punti":
			{
			label: "Punteggio <?php echo $this->giocatore->cognome ." ". $this->giocatore->nome; ?>",
			data: [<?php $i = 0; foreach($this->giocatore->voti as $key => $val): $i++; ?><?php echo ($val->voto != '0') ? '[' . $key . ',' . $val->voto . ']' : ''; echo ($val->voto != '0' && count($this->giocatore->voti) != $i) ? ',' : ''; endforeach; ?>]
			}
		};
// ]]>
</script>
<?php endif; ?>
