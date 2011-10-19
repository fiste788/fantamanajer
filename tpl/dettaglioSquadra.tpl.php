<?php $r = 'Por.'; ?>
<div id="headerSquadra">
	<?php if(file_exists(UPLOADDIR . $this->squadraDett->id . '.jpg')): ?>
	<div class="column last">
		<a title="<?php echo $this->squadraDett->nomeSquadra; ?>" href="<?php echo UPLOADURL . $this->squadraDett->id . '-original.jpg'; ?>" class="fancybox column">
			<img <?php $appo = getimagesize(UPLOADDIR . $this->squadraDett->id . '.jpg'); echo $appo[3]; ?> alt="<?php echo $this->squadraDett->id; ?>" src="<?php echo UPLOADURL . $this->squadraDett->id . '.jpg'; ?>" title="Logo <?php echo $this->squadraDett->nomeSquadra; ?>" />
		</a>
	</div>
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
		<p id="mex">Se vuoi modificare le tue informazioni personali come mail, nome, password
		<?php if(GIORNATA <= 2): ?>. Fino alla seconda giornata imposta quì anche il nome della tua squadra <?php endif; ?><a id="qui">Clicca quì</a></p>
		<div id="datiNascosti" class="hidden no-margin">
			<form enctype="multipart/form-data" id="userdata" action="<?php echo Links::getLink('dettaglioSquadra',array('squadra'=>$_GET['squadra'])); ?>" method="post">
				<fieldset class="column no-margin no-padding">
					<div class="column">
						<div class="formbox">
							<label for="name">Nome:</label>
							<input id="name" class="text" type="text" maxlength="15" name="nomeProp" value="<?php echo $this->squadraDett->nome; ?>"/>
						</div>
						<div class="formbox">
							<label for="surname">Cognome:</label>
							<input id="surname" class="text" type="text" maxlength="15" name="cognome"  value="<?php echo $this->squadraDett->cognome; ?>"/>
						</div>
						<div class="formbox">
							<label for="email">E-mail:</label>
							<input id="email" class="text" type="text" maxlength="30" name="mail"  value="<?php echo $this->squadraDett->email; ?>"/>
						</div>
						<div class="formbox">
							<label for="abilitaMail">Ricevi email:</label>
							<input id="abilitaMail" class="checkbox" type="checkbox" name="abilitaMail"<?php echo ($this->squadraDett->abilitaMail == 1) ? ' checked="checked"' : ''; ?>/>
						</div>
					</div>
					<div class="column">
						<?php if(GIORNATA <= 2): ?>
						<div class="formbox">
							<label for="nomeSquadra">Nome squadra:</label>
							<input id="nomeSquadra" class="text" type="text" maxlength="30" name="nome"  value="<?php echo $this->squadraDett->nome; ?>"/>
						</div>
						<?php endif; ?>
						<div class="formbox">
							<label for="password">Password:</label>
							<input id="password" class="text" type="password" maxlength="12" name="passwordnew"/>
						</div>
						<div class="formbox">
							<label for="passwordrepeat">Ripeti Pass:</label>
							<input id="passwordrepeat" class="text" type="password" maxlength="12" name="passwordnewrepeat"/>
						</div>
					</div>
					<div class="column">
						<h4 class="no-margin">Carica il tuo logo:</h4>
						<input class="upload" name="userfile" type="file" /><br />
						<input type="submit" class="submit" name="submit" value="OK" />
					</div>
				</fieldset>
			</form>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php if(!empty($this->giocatori)): ?>
	<h3>Giocatori</h3>
	<table id="rosa" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<th>Nome</th>
				<th class="center">Ruolo</th>
				<th class="center">Club</th>
				<th class="center">PG</th>
				<th class="center">MVoti</th>
				<th class="center">MPunti</th>
				<th class="center">Gol</th>
				<th class="center">Gol subiti</th>
				<th class="center">Assist</th>
				<th class="center">Ammonizioni</th>
				<th class="center">Esplusioni</th>
			</tr>
			<?php foreach($this->giocatori as $key => $val): ?>
			<tr class="tr <?php if($val->status == 0) echo 'rosso'; else echo 'row'; ?>">
				<td title="" class="name<?php if($val->ruolo != $r) echo ' ult'; ?>">
					<a href="<?php echo Links::getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val->id)); ?>"><?php echo $val->cognome . ' ' . $val->nome; ?></a>
				</td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $val->ruolo; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><a target="_blank" href="<?php echo Links::getLink('dettaglioClub',array('club'=>$val->idClub)); ?>"><?php echo (!empty($val->nomeClub)) ? strtoupper(substr($val->nomeClub,0,3)) : "&nbsp;"; ?></a></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $val->presenze . " (" . $val->presenzeVoto . ")"; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo (!empty($val->avgVoti)) ? $val->avgVoti : "&nbsp;"; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo (!empty($val->avgPunti)) ? $val->avgPunti : "&nbsp;"; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo (!empty($val->gol)) ? $val->gol : "&nbsp;"; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo (!empty($val->golSubiti)) ? $val->golSubiti : "&nbsp;"; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo (!empty($val->assist)) ? $val->assist : "&nbsp;"; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo (!empty($val->ammonizioni)) ? $val->ammonizioni : "&nbsp;"; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo (!empty($val->espulsioni)) ? $val->espulsioni : "&nbsp;"; ?></td>
			</tr>
			<?php $r = $val->ruolo; ?>
			<?php endforeach; ?>
			<tr>
				<td colspan="4">Totali</td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $this->squadraDett->avgVoti; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $this->squadraDett->avgPunti; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $this->squadraDett->totaleGol; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $this->squadraDett->totaleGolSubiti; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $this->squadraDett->totaleAssist; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $this->squadraDett->totaleAmmonizioni; ?></td>
				<td class="tdcenter<?php echo ($val->ruolo != $r) ? ' ult' : ''; ?>"><?php echo $this->squadraDett->totaleEspulsioni; ?></td>
			</tr>
		</tbody>
	</table>
<?php endif;?>
