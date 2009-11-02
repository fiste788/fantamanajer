<?php $r='Por.'; ?>
<div id="squadre" class="main-content">
	<div class="column last">
		<div id="imageContainer">
			<a title="<?php echo $this->squadradett['nome'] ?>" href="<?php echo UPLOADIMGURL.$this->squadradett['idUtente'].'-original.jpg'; ?>" class="fancybox column">
				<img alt="<?php echo $this->squadradett['idUtente']; ?>" src="<?php echo UPLOADIMGURL. $this->squadradett['idUtente'].'.jpg'; ?>" title="Logo <?php echo $this->squadradett['nome']; ?>" />
			</a>
			<?php if($this->squadradett['idUtente'] == $_SESSION['idSquadra']): ?>
			<form enctype="multipart/form-data" id="formupload" name="uploadlogo" action="<?php echo $this->linksObj->getLink('rosa',array('squadra'=>$_GET['squadra'])); ?>" method="post">
				<h4 class="no-margin">Carica il tuo logo:</h4>
					<input class="upload" name="userfile" type="file" />
					<input type="submit" class="submit" value="Invia file" />
			</form>
			<?php endif; ?>
		</div>
		<h2 id="nomeSquadra" class="column"><?php echo $this->squadradett['nome']; ?></h2>
		<div id="dettaglioSquadra">
			<p>
				<span class="bold">Proprietario:</span>
				<?php echo $this->squadradett['nomeProp'] . " " . $this->squadradett['cognome']; ?>
			</p>
			<p>
				<span class="bold">Username:</span>
				<?php echo $this->squadradett['username']; ?>
			</p>
			<p>
				<span class="bold">E-mail:</span>
				<?php echo $this->squadradett['mail']; ?>
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
						<?php if($this->squadradett['idUtente'] == $_SESSION['idSquadra']): ?>
						<p class="column" id="mex">Se vuoi modificare le tue informazioni personali come mail, nome, password
						<?php if(GIORNATA <= 2): ?>. Fino alla seconda giornata imposta quì anche il nome della tua squadra <?php endif; ?><a href="">Clicca qui</a></p>
					</div>
					<div class="hidden no-margin">
						<form id="userdata" action="<?php echo $this->linksObj->getLink('rosa',array('squadra'=>$_GET['squadra'])); ?>" name="data" method="post">
							<?php if(GIORNATA <= 2): ?>
							<div class="formbox">
								<label for="nomeSquadra">Nome squadra:</label>
								<input id="nomeSquadra" class="text" type="text" maxlength="30" name="nome"  value="<?php echo $this->squadradett['nome']; ?>"/>
							</div>
							<?php endif; ?>
							<div class="formbox">
								<label for="name">Nome:</label>
								<input id="name" class="text" type="text" maxlength="15" name="nomeProp" value="<?php echo $this->squadradett['nomeProp']; ?>"/>
							</div>
							<div class="formbox">
								<label for="surname">Cognome:</label>
								<input id="surname" class="text" type="text" maxlength="15" name="cognome"  value="<?php echo $this->squadradett['cognome']; ?>"/>
							</div>
							<div class="formbox">
								<label for="username">Username:</label>
								<input id="username" class="text" type="text" maxlength="15" name="usernamenew"  value="<?php echo $this->squadradett['username']; ?>"/>
							</div>
							<div class="formbox">
								<label for="email">E-mail:</label>
								<input id="email" class="text" type="text" maxlength="30" name="mail"  value="<?php echo $this->squadradett['mail']; ?>"/>
							</div>
							<div class="formbox">
								<label for="abilitaMail">Ricevi email:</label>
								<input id="abilitaMail" class="checkbox" type="checkbox" name="abilitaMail"<?php if($this->squadradett['abilitaMail'] == 1) echo ' checked="checked"' ?>/>
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
<h3>Giocatori</h3>
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
			</tr>
			<?php if(!empty($this->giocatori)): ?>
			<?php foreach($this->giocatori as $key => $val): ?>
			<tr class="tr <?php if(empty($val['club'])) echo 'rosso'; else echo 'row' ?>">
				<td title="" class="name<?php if($val['ruolo'] != $r) echo ' ult' ?>">
					<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val['idGioc'])); ?>"><?php echo $val['nome']; ?></a>
				</td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php echo $val['ruolo']; ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php if(!empty($val['club'])) echo strtoupper(substr($val['club'],0,3)); else echo "&nbsp;" ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php echo $val['presenze']." (".$val['presenzeEff'].")"; ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"<?php if(!empty($val['avgvoto'])) echo ' title="' . $val['avgvoto'] . '"'; ?>><?php if(!empty($val['avgvoto'])) echo $val['avgvoto']; else echo "&nbsp;" ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"<?php if(!empty($val['avgpunti'])) echo ' title="' . $val['avgpunti'] . '"'; ?>><?php if(!empty($val['avgpunti'])) echo $val['avgpunti']; else echo "&nbsp;" ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php if(!empty($val['gol'])) echo $val['gol']; else echo "&nbsp;" ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php if(!empty($val['assist'])) echo $val['assist']; else echo "&nbsp;" ?></td>
			</tr>
			<?php $r = $val ['ruolo'];  ?>
			<?php endforeach; ?>
			<?php endif;?>
			<tr>
				<?php if(!empty($this->giocatori)): ?>
				<td class="ult" colspan="3">Media</td>
				<td class="ult tdcenter" title="<?php echo $this->mediaPartite; ?>"><?php echo $this->mediaPartite; ?></td>
				<td class="ult tdcenter" title="<?php echo $this->mediaVoto; ?>"><?php echo $this->mediaVoto; ?></td>
				<td class="ult tdcenter" title="<?php echo $this->mediaPunti; ?>"><?php echo $this->mediaPunti; ?></td>
				<td class="ult tdcenter" title="<?php echo $this->mediaGol; ?>"><?php echo $this->mediaGol; ?></td>
				<td class="ult tdcenter" title="<?php echo $this->mediaAssist; ?>"><?php echo $this->mediaAssist; ?></td>
				<?php else: ?>
				<td class="ult" colspan="3"><br><br>Nessun giocatore in rosa</td>
				<?php endif; ?>
			</tr>
		</tbody>
	</table>
</div>
<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
	<?php if (isset($this->message)): ?>
		<?php if($this->message[0] == 1): ?>									
			<div id="messaggio" class="messaggio bad column last">
				<img alt="!" src="<?php echo IMGSURL.'attention-bad-big.png'; ?>" title="Attenzione!" />
				<span><?php echo $this->message[1]; ?></span>
			</div>
		<?php elseif($this->message[0] == 0): /* TUTTO OK */?>
			<div id="messaggio" class="messaggio good column last">
				<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
				<span><?php echo $this->message[1]; ?></span>
			</div>
		<?php endif; ?>
		<script type="text/javascript">
		window.onload = (function(){
 			$("#messaggio").effect("pulsate", { times: 3 }, 1000);
			$("#messaggio").click(function () {
				$("div#messaggio").fadeOut("slow");
			});
 		});
		</script>
	<?php endif; ?>
		<?php if($_SESSION['logged'] == TRUE): ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		<?php endif; ?>
		<div id="operazioni-other" class="column last">
		<ul class="operazioni-content">
				<?php if(!$this->squadraprec): ?>
					<li class="simil-link undo-punteggi-unactive column last">Squadra precedente</li>
				<?php else: ?>
					<li class="column last"><a title="<?php echo $this->elencosquadre[$this->squadraprec]['nome'];?>" class="undo-punteggi-active column last operazione" href="<?php echo $this->linksObj->getLink('rosa',array('squadra'=>$this->squadraprec)); ?>">Squadra precedente</a></li>
				<?php endif; ?>
				<?php if(!$this->squadrasucc): ?>
					<li class="simil-link redo-punteggi-unactive column last">Squadra successiva</li>
				<?php else: ?>
				<li class="column last"><a title="<?php echo $this->elencosquadre[$this->squadrasucc]['nome'];?>" class="redo-punteggi-active column last operazione" href="<?php echo $this->linksObj->getLink('rosa',array('squadra'=>$this->squadrasucc)); ?>">Squadra successiva</a></li>
				<?php endif; ?>
			</ul>
		</div>

		<?php if(file_exists(UPLOADDIR. $this->squadradett['idUtente'].'.jpg')): ?>
			<a title="<?php echo $this->squadradett['nome'] ?>" href="<?php echo UPLOADIMGURL. $this->squadradett['idUtente'].'-original.jpg'; ?>" class="fancybox">
				<img class="logo" alt="<?php echo $this->squadradett['idUtente']; ?>" src="<?php echo UPLOADIMGURL. $this->squadradett['idUtente'].'.jpg'; ?>" title="Logo <?php echo $this->squadradett['nome']; ?>" />
				<img class="logo reflex" alt="<?php echo $this->squadradett['idUtente']; ?>" src="<?php echo UPLOADIMGURL. $this->squadradett['idUtente'].'-reflex.jpg'; ?>" title="Logo <?php echo $this->squadradett['nome']; ?>" />
			</a>
		<?php endif; ?>
		</div>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
