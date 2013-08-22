<?php $r = 'Por.'; ?>
<div id="header-squadra" class="clearfix">
    <?php if ($this->squadraDett->id == $_SESSION['idUtente']): ?>
        <input id="fileupload" type="file" name="files" data-url="<?php echo AJAXURL ?>upload.php" class="hidden">
        <div id="dropzone">
            <?php if (file_exists(UPLOADDIR . 'thumb/' . $this->squadraDett->id . '.jpg')): ?>
                <a title="<?php echo $this->squadraDett->nomeSquadra; ?>" href="<?php echo UPLOADURL . $this->squadraDett->id . '.jpg'; ?>" class="fancybox logo left">
                    <img class="image-polaroid" <?php $appo = getimagesize(UPLOADDIR . 'thumb/' . $this->squadraDett->id . '.jpg');echo $appo[3]; ?> alt="<?php echo $this->squadraDett->id; ?>" src="<?php echo UPLOADURL . 'thumb/' . $this->squadraDett->id . '.jpg'; ?>" title="Logo <?php echo $this->squadraDett->nomeSquadra; ?>" />
                </a>
            <?php else: ?>
                <div class="well">Trascina l'immagine qui per caricarla</div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?php if (file_exists(UPLOADDIR . 'thumb/' . $this->squadraDett->id . '.jpg')): ?>
            <a title="<?php echo $this->squadraDett->nomeSquadra; ?>" href="<?php echo UPLOADURL . $this->squadraDett->id . '.jpg'; ?>" class="fancybox logo left">
               <img class="img-polaroid" <?php $appo = getimagesize(UPLOADDIR . 'thumb/' . $this->squadraDett->id . '.jpg');echo $appo[3]; ?> alt="<?php echo $this->squadraDett->id; ?>" src="<?php echo UPLOADURL . 'thumb/' . $this->squadraDett->id . '.jpg'; ?>" title="Logo <?php echo $this->squadraDett->nomeSquadra; ?>" />
            </a>
        <?php endif; ?>
    <?php endif; ?>

	<h2 id="nomeSquadra"><?php echo $this->squadraDett->nomeSquadra; ?></h2>
	<div id="datiSquadra">
		<div id="mostraDati">
			<p>
				<span class="bold">Proprietario:</span>
				<?php echo $this->squadraDett->nome . " " . $this->squadraDett->cognome; ?>
			</p>
			<p>
				<span class="bold">Username:</span>
				<?php echo $this->squadraDett->username; ?>
			</p>
			<p>
				<span class="bold">E-mail:</span>
				<?php echo $this->squadraDett->email; ?>
			</p>
			<p>
				<span class="bold">Media punti:</span>
				<?php echo $this->squadraDett->punteggioMed; ?>
			</p>
			<p>
				<span class="bold">Punti min:</span>
				<?php echo $this->squadraDett->punteggioMin; ?>
			</p>
			<p>
				<span class="bold">Punti max:</span>
				<?php echo $this->squadraDett->punteggioMax; ?>
			</p>
		</div>
	</div>
</div>
<?php if($this->squadraDett->id == $_SESSION['idUtente']): ?>
	<p class="alert-message alert alert-info">Se vuoi modificare le tue informazioni personali come mail, nome, password <a href="<?php echo ""; ?>">Clicca quì</a></p>
<?php endif; ?>
<?php if(!empty($this->giocatori)): ?>
<div class="clearfix well">
	<h3>Giocatori</h3>
	<table class="table tablesorter">
		<thead>
			<tr>
				<th>Nome</th>
				<th>Ruolo</th>
				<th>Club</th>
                <th><abbr title="Partite giocate">PG</abbr></th>
                <th><abbr title="Media voto">MV</abbr></th>
                <th><abbr title="Media punti">MP</abbr></th>
				<th class="hidden-xs">Gol</th>
				<th class="hidden-xs">Gol subiti</th>
				<th class="hidden-xs">Assist</th>
                <th class="hidden-xs"><abbr title="Ammonizioni">Amm</abbr></th>
                <th class="hidden-xs"><abbr title="Espulsioni">Esp</abbr></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->giocatori as $giocatore): ?>
                <tr class="tr<?php if(!$giocatore->isAttivo()) echo ' rosso'; ?>">
                    <td title="" class="name">
                        <a href="<?php echo $this->router->generate("giocatore_show",array('id'=>$giocatore->id)); ?>"><?php echo $giocatore->cognome . ' ' . $giocatore->nome; ?></a>
                    </td>
                    <td><?php echo $giocatore->ruolo; ?></td>
                    <td><a href="<?php echo $this->router->generate("club_show",array('id'=>$giocatore->idClub)) ?>"><?php echo (!empty($giocatore->nomeClub)) ? strtoupper(substr($giocatore->nomeClub,0,3)) : "&nbsp;"; ?></a></td>
                    <td><?php echo $giocatore->presente . " (" . $giocatore->presenzeVoto . ")"; ?></td>
                    <td><?php echo $giocatore->avgVoti ?></td>
                    <td><?php echo $giocatore->avgPunti ?></td>
                    <td class="hidden-xs"><?php echo $giocatore->gol ?></td>
                    <td class="hidden-xs"><?php echo $giocatore->golSubiti ?></td>
                    <td class="hidden-xs"><?php echo $giocatore->assist ?></td>
                    <td class="hidden-xs"><?php echo $giocatore->ammonizioni ?></td>
                    <td class="hidden-xs"><?php echo $giocatore->espulsioni ?></td>
                </tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4">Totali</td>
				<td class="tdcenter"><?php echo $this->squadraDett->avgVoti; ?></td>
				<td class="tdcenter"><?php echo $this->squadraDett->avgPunti; ?></td>
				<td class="tdcenter hidden-xs"><?php echo $this->squadraDett->totaleGol; ?></td>
				<td class="tdcenter hidden-xs "><?php echo $this->squadraDett->totaleGolSubiti; ?></td>
				<td class="tdcenter hidden-xs"><?php echo $this->squadraDett->totaleAssist; ?></td>
				<td class="tdcenter hidden-xs"><?php echo $this->squadraDett->totaleAmmonizioni; ?></td>
				<td class="tdcenter hidden-xs"><?php echo $this->squadraDett->totaleEspulsioni; ?></td>
			</tr>
		</tfoot>
	</table>
</div>
<?php endif;?>
