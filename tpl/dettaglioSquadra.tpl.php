<?php $r='Por.'; ?>
<div id="squadre" class="main-content">
	<div>
		<a title="<?php echo $this->squadraDett['nome'] ?>" href="<?php echo UPLOADIMGURL.$this->squadraDett['idUtente'].'-original.jpg'; ?>" class="fancybox column">
			<img alt="<?php echo $this->squadraDett['idUtente']; ?>" src="<?php echo UPLOADIMGURL. $this->squadraDett['idUtente'].'.jpg'; ?>" title="Logo <?php echo $this->squadraDett['nome']; ?>" />
		</a>
		<?php if($this->squadraDett['idUtente'] == $_SESSION['idSquadra']): ?>
		<form enctype="multipart/form-data" id="formupload" name="uploadlogo" action="<?php echo $this->linksObj->getLink('dettaglioSquadra',array('squadra'=>$_GET['squadra'])); ?>" method="post">
			<h4 class="no-margin">Carica il tuo logo:</h4>
			<input class="upload" name="userfile" type="file" /><br />
			<input type="submit" class="submit" value="Invia file" />
		</form>
		<?php endif; ?>
		<h2 id="nomeSquadra"><?php echo $this->squadraDett['nome']; ?></h2>
		<div id="dettaglioSquadra">
			<p>
				<span class="bold">Proprietario:</span>
				<?php echo $this->squadraDett['nomeProp'] . " " . $this->squadraDett['cognome']; ?>
			</p>
			<p>
				<span class="bold">Username:</span>
				<?php echo $this->squadraDett['username']; ?>
			</p>
			<p>
				<span class="bold">E-mail:</span>
				<?php echo $this->squadraDett['mail']; ?>
			</p>
			<p>
				<span class="bold">Media punti:</span>
				<?php echo $this->media; ?>
			</p>
			<p>
				<span class="bold">Punti min:</span>
				<?php echo $this->min; ?>
			</p>
			<p>
				<span class="bold">Punti max:</span>
				<?php echo $this->max; ?>
			</p>
						<?php if($this->squadraDett['idUtente'] == $_SESSION['idSquadra']): ?>
						<p id="mex">Se vuoi modificare le tue informazioni personali come mail, nome, password
						<?php if(GIORNATA <= 2): ?>. Fino alla seconda giornata imposta quì anche il nome della tua squadra <?php endif; ?><a href="">Clicca qui</a></p>
					<div class="hidden no-margin">
						<form id="userdata" action="<?php echo $this->linksObj->getLink('rosa',array('squadra'=>$_GET['squadra'])); ?>" name="data" method="post">
							<?php if(GIORNATA <= 2): ?>
							<div class="formbox">
								<label for="nomeSquadra">Nome squadra:</label>
								<input id="nomeSquadra" class="text" type="text" maxlength="30" name="nome"  value="<?php echo $this->squadraDett['nome']; ?>"/>
							</div>
							<?php endif; ?>
							<div class="formbox">
								<label for="name">Nome:</label>
								<input id="name" class="text" type="text" maxlength="15" name="nomeProp" value="<?php echo $this->squadraDett['nomeProp']; ?>"/>
							</div>
							<div class="formbox">
								<label for="surname">Cognome:</label>
								<input id="surname" class="text" type="text" maxlength="15" name="cognome"  value="<?php echo $this->squadraDett['cognome']; ?>"/>
							</div>
							<div class="formbox">
								<label for="username">Username:</label>
								<input id="username" class="text" type="text" maxlength="15" name="usernamenew"  value="<?php echo $this->squadraDett['username']; ?>"/>
							</div>
							<div class="formbox">
								<label for="email">E-mail:</label>
								<input id="email" class="text" type="text" maxlength="30" name="mail"  value="<?php echo $this->squadraDett['mail']; ?>"/>
							</div>
							<div class="formbox">
								<label for="abilitaMail">Ricevi email:</label>
								<input id="abilitaMail" class="checkbox" type="checkbox" name="abilitaMail"<?php if($this->squadraDett['abilitaMail'] == 1) echo ' checked="checked"' ?>/>
							</div>
							<div class="formbox">
								<label for="password">Password:</label>
								<input id="password" class="text" type="password" maxlength="12" name="passwordnew"/>
							</div>
							<div class="formbox">
								<label for="passwordrepeat">Ripeti Pass:</label>
								<input id="passwordrepeat" class="text" type="password" maxlength="12" name="passwordnewrepeat"/>
							</div>
							<input type="submit" class="submit" value="OK" />
						</form>
					</div>
				<?php endif; ?>
		</div>
	</div>
	<h3 style="clear:both;">Giocatori</h3>
	<table id="rosa" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<th>Nome</th>
				<th class="tdcenter">Ruolo</th>
				<th class="tdcenter">Club</th>
				<th class="tdcenter">PG</th>
				<th class="tdcenter">MVoti</th>
				<th class="tdcenter">MPunti</th>
				<th class="tdcenter">Gol</th>
				<th class="tdcenter">Assist</th>
				<th class="tdcenter">Ammonizioni</th>
				<th class="tdcenter">Esplusioni</th>
			</tr>
			<?php if(!empty($this->giocatori)): ?>
			<?php foreach($this->giocatori as $key => $val): ?>
			<tr class="tr <?php if(empty($val['idClub'])) echo 'rosso'; else echo 'row' ?>">
				<td title="" class="name<?php if($val['ruolo'] != $r) echo ' ult' ?>">
					<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val['idGioc'])); ?>"><?php echo $val['cognome'] . ' ' . $val['nome']; ?></a>
				</td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php echo $val['ruolo']; ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php if(!empty($val['club'])) echo strtoupper(substr($val['club'],0,3)); else echo "&nbsp;" ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php echo $val['presenze']." (".$val['presenzeVoto'].")"; ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php if(!empty($val['avgVoti'])) echo $val['avgVoti']; else echo "&nbsp;" ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php if(!empty($val['avgPunti'])) echo $val['avgPunti']; else echo "&nbsp;" ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php if(!empty($val['gol'])) echo $val['gol']; else echo "&nbsp;" ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php if(!empty($val['assist'])) echo $val['assist']; else echo "&nbsp;" ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php if(!empty($val['ammonizioni'])) echo $val['ammonizioni']; else echo "&nbsp;" ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php if(!empty($val['espulsioni'])) echo $val['espulsioni']; else echo "&nbsp;" ?></td>
			</tr>
			<?php $r = $val ['ruolo'];  ?>
			<?php endforeach; ?>
			<?php endif;?>
		</tbody>
	</table>
</div>
