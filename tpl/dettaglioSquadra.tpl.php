<?php $r = 'Por.'; ?>
<div id="headerSquadra">
	<?php if(file_exists(UPLOADDIR . 'thumb/' . $this->squadraDett->id . '.jpg')): ?>
    	<a title="<?php echo $this->squadraDett->nomeSquadra; ?>" href="<?php echo UPLOADURL . $this->squadraDett->id . '.jpg'; ?>" class="fancybox logo left">
        	<img class="img-polaroid" <?php $appo = getimagesize(UPLOADDIR . 'thumb/' . $this->squadraDett->id . '.jpg'); echo $appo[3]; ?> alt="<?php echo $this->squadraDett->id; ?>" src="<?php echo UPLOADURL . 'thumb/' . $this->squadraDett->id . '.jpg'; ?>" title="Logo <?php echo $this->squadraDett->nomeSquadra; ?>" />
        </a>
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
		<?php if($this->squadraDett->id == $_SESSION['idUtente']): ?>
			<p class="alert-message alert alert-info">Se vuoi modificare le tue informazioni personali come mail, nome, password <a href="<?php echo Links::getLink('utente') ?>">Clicca quì</a></p>
		<?php endif; ?>
	</div>
</div>
<?php if(!empty($this->giocatori)): ?>
<div class="clear">
	<h3>Giocatori</h3>
	<table class="table tablesorter">
		<thead>
			<tr>
				<th>Nome</th>
				<th class="center">Ruolo</th>
				<th class="center">Club</th>
                <th class="center"><abbr title="Partite giocate">PG</abbr></th>
                <th class="center"><abbr title="Media voto">MV</abbr></th>
                <th class="center"><abbr title="Media punti">MP</abbr></th>
				<th class="hidden-phone center">Gol</th>
				<th class="hidden-phone center">Gol subiti</th>
				<th class="hidden-phone center">Assist</th>
                <th class="hidden-phone center"><abbr title="Ammonizioni">Amm</abbr></th>
                <th class="hidden-phone center"><abbr title="Espulsioni">Esp</abbr></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->giocatori as $val): ?>
			<tr class="tr<?php if(!$val->isAttivo()) echo ' rosso'; ?>">
				<td title="" class="name<?php if($val->ruolo != $r) echo ' ult'; ?>">
					<a href="<?php echo Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->id)); ?>"><?php echo $val->cognome . ' ' . $val->nome; ?></a>
				</td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $val->ruolo; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><a href="<?php echo Links::getLink('dettaglioClub',array('club'=>$val->idClub)); ?>"><?php echo (!empty($val->nomeClub)) ? strtoupper(substr($val->nomeClub,0,3)) : "&nbsp;"; ?></a></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $val->presente . " (" . $val->presenzeVoto . ")"; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo (!empty($val->avgVoti)) ? $val->avgVoti : "&nbsp;"; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo (!empty($val->avgPunti)) ? $val->avgPunti : "&nbsp;"; ?></td>
				<td class="hidden-phone tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo (!empty($val->gol)) ? $val->gol : "&nbsp;"; ?></td>
				<td class="hidden-phone tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo (!empty($val->golSubiti)) ? $val->golSubiti : "&nbsp;"; ?></td>
				<td class="hidden-phone tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo (!empty($val->assist)) ? $val->assist : "&nbsp;"; ?></td>
				<td class="hidden-phone tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo (!empty($val->ammonizioni)) ? $val->ammonizioni : "&nbsp;"; ?></td>
				<td class="hidden-phone tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo (!empty($val->espulsioni)) ? $val->espulsioni : "&nbsp;"; ?></td>
			</tr>
			<?php $r = $val->ruolo; ?>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4">Totali</td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $this->squadraDett->avgVoti; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $this->squadraDett->avgPunti; ?></td>
				<td class="hidden-phone tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $this->squadraDett->totaleGol; ?></td>
				<td class="hidden-phone tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $this->squadraDett->totaleGolSubiti; ?></td>
				<td class="hidden-phone tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $this->squadraDett->totaleAssist; ?></td>
				<td class="hidden-phone tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $this->squadraDett->totaleAmmonizioni; ?></td>
				<td class="hidden-phone tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $this->squadraDett->totaleEspulsioni; ?></td>
			</tr>
		</tfoot>
	</table>
</div>
<?php endif;?>
