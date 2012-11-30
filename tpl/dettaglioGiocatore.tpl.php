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
        <figure>
            <img class="img-polaroid foto left" alt="<?php echo $this->giocatore; ?>" src="<?php echo $this->pathFoto; ?>" />
        </figure>
		<div class="column">
			<p><?php echo $this->ruoli[$this->giocatore->ruolo]; ?></p>
			<?php if($_SESSION['logged']): ?><p>Squadra: <?php echo $this->label; ?></p><?php endif; ?>
			<p>Presenze: <?php echo $this->giocatore->presente . " (" . $this->giocatore->presenzeVoto . ")"; ?></p>
			<p>Gol: <?php if($this->giocatore->ruolo != 'P') echo $this->giocatore->gol; elseif($this->giocatore->golSubiti == 0) echo $this->giocatore->golSubiti; else "-" . $this->giocatore->golSubiti; ?></p>
			<p>Assist: <?php echo $this->giocatore->assist; ?></p>
			<p>Media voti: <?php echo (!empty($this->giocatore->avgVoti)) ? $this->giocatore->avgVoti : ''; ?></p>
			<p>Media punti: <?php echo (!empty($this->giocatore->avgPunti)) ? $this->giocatore->avgPunti : ''; ?></p>
		</div>
		<?php if($this->giocatore->nomeClub != NULL): ?>
			<a class="right hidden-small-phone" href="<?php echo Links::getLink('dettaglioClub',array('club'=>$this->giocatore->idClub)); ?>">
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
<table class="table">
    <thead>
        <tr>
            <th><abbr title="Giornata">Giorn</abbr></th>
            <th>Punti</th>
            <th>Voti</th>
            <th<?php if($this->giocatore->ruolo == "P") echo ' class="hidden-small-phone"' ?>>Gol</th>
            <th<?php if($this->giocatore->ruolo != "P") echo ' class="hidden-small-phone"' ?>><abbr title="Gol subiti">Gol S</abbr></th>
            <th>Assist</th>
            <th class="hidden-small-phone"><abbr title="Rigori">Rig</abbr></th>
            <th class="hidden-small-phone"><abbr title="Rigori subiti">Rig S</abbr></th>
            <th><abbr title="Ammonito"><i class="ammonizione"></i></abbr></th>
            <th><abbr title="Espulso"><i class="espulsione"></i></abbr></th>
            <th><abbr title="Titolare">Tit</abbr></th>
            <th class="hidden-small-phone"><abbr title="Quotazione">Quot</abbr></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($this->giocatore->voti as $key => $val): ?>
            <tr>
                <td><?php echo $val->getIdGiornata(); ?></td>
                <td><?php echo $val->getPunti(); ?></td>
                <td><?php echo ($val->getVoto() != '0') ? $val->getVoto() : "&nbsp;"; ?></td>
                <td<?php if($this->giocatore->ruolo == "P") echo ' class="hidden-small-phone"' ?>><?php echo $val->getGol(); ?></td>
                <td<?php if($this->giocatore->ruolo != "P") echo ' class="hidden-small-phone"' ?>><?php echo $val->getGolSubiti(); ?></td>
                <td><?php echo $val->getAssist(); ?></td>
                <td class="hidden-small-phone"><?php echo $val->getRigoriSegnati(); ?></td>
                <td class="hidden-small-phone"><?php echo $val->getRigoriSubiti(); ?></td>
                <td><?php if($val->isAmmonito()): ?><i class="icon-ok"></i><?php endif; ?></td>
                <td><?php if($val->isEspulso()): ?><i class="icon-ok"></i><?php endif; ?></td>
                <td><?php if($val->isTitolare()): ?><i class="icon-ok"></i><?php endif; ?></td>
                <td class="hidden-small-phone"><?php echo $val->getQuotazione(); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div id="grafico" class="hidden-phone">
	<div id="placeholder" style="height:300px;"></div>
    <div id="overview" style="width:200px;height:100px;"></div>
    <p>Seleziona sulla miniatura una parte di grafico per ingrandirla. Per questa funzionalità si consiglia di usare browser come Safari, Firefox o Opera invece di altri meno performanti come Internet Explorer</p><p class="column" id="selection">&nbsp;</p>
    <div id="hidden" class="hidden"></div>
    <a id="clearSelection" class="hidden">(Cancella selezione)</a>
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
