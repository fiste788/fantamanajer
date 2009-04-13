<?php $r='Por.'; ?>
<?php if($this->squadra != NULL): ?>
<div class="titolo-pagina">
	<div class="column logo-tit">
		<?php if(file_exists(UPLOADDIR. $this->squadradett['idUtente'].'-small.jpg')): ?>
			<a title="<?php echo $this->squadradett['nome'] ?>" href="<?php echo UPLOADIMGURL.$this->squadradett['idUtente'].'-original.jpg'; ?>" class="fancybox">
				<img alt="<?php echo $this->squadradett['idUtente']; ?>" src="<?php echo UPLOADIMGURL. $this->squadradett['idUtente'].'-small.jpg'; ?>" title="Logo <?php echo $this->squadradett['nome']; ?>" />
				<img class="reflex" align="left" alt="<?php echo $this->squadradett['idUtente']; ?>" src="<?php echo UPLOADIMGURL. $this->squadradett['idUtente'].'-small-reflex.jpg'; ?>" title="Logo <?php echo $this->squadradett['nome']; ?>" />
			</a>
		<?php else: ?>
			<img align="left" src="<?php echo IMGSURL.'rose-big.png'; ?>" alt="Logo Squadre" />
		<?php endif; ?>
	</div>
	<h2 class="column"><?php echo $this->squadradett['nome'] ?></h2>
</div>
<div id="squadre" class="main-content">
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
			<?php foreach($this->giocatori as $key => $val): ?>
			<tr class="tr <?php if(empty($val['club'])) echo 'rosso'; else echo 'row' ?>">	
				<td title="" class="name<?php if($val['ruolo'] != $r) echo ' ult' ?>">
					<a href="<?php echo $this->linksObj->getLink('dettaglioGiocatore',array('edit'=>'view','id'=>$val['idGioc'])); ?>"><?php echo $val['nome']; ?></a>
				</td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php echo $val['ruolo']; ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php if(!empty($val['club'])) echo strtoupper(substr($val['club'],0,3)); else echo "&nbsp;" ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php echo $val['partite']." (".$val['partiteEff'].")"; ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"<?php if(!empty($val['votoEffAll'])) echo ' title="' . $val['votoEffAll'] . '"'; ?>><?php if(!empty($val['votoEff'])) echo $val['votoEff']; else echo "&nbsp;" ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"<?php if(!empty($val['votoEffAll'])) echo ' title="' . $val['votiAll'] . '"'; ?>><?php if(!empty($val['voti'])) echo $val['voti']; else echo "&nbsp;" ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php if(!empty($val['gol'])) echo $val['gol']; else echo "&nbsp;" ?></td>
				<td class="tdcenter<?php if($val['ruolo'] != $r) echo ' ult' ?>"><?php if(!empty($val['assist'])) echo $val['assist']; else echo "&nbsp;" ?></td>
			</tr>
			<?php $r = $val ['ruolo'];  ?>
			<?php endforeach; ?>
			<tr>
				<td class="ult" colspan="3">Media</td>
				<td class="ult tdcenter" title="<?php echo $this->mediaPartiteAll; ?>"><?php echo $this->mediaPartite; ?></td>
				<td class="ult tdcenter" title="<?php echo $this->mediaVotoAll; ?>"><?php echo $this->mediaVoto; ?></td>
				<td class="ult tdcenter" title="<?php echo $this->mediaMagicAll; ?>"><?php echo $this->mediaMagic; ?></td>
				<td class="ult tdcenter" title="<?php echo $this->mediaGolAll; ?>"><?php echo $this->mediaGol; ?></td>
				<td class="ult tdcenter" title="<?php echo $this->mediaAssistAll; ?>"><?php echo $this->mediaAssist; ?></td>
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
		<?php if(strcasecmp($this->squadradett['username'], $_SESSION['userid']) == 0): ?>
			<form enctype="multipart/form-data" id="formupload" name="uploadlogo" action="<?php echo $this->linksObj->getLink('rosa',array('squadra'=>$_GET['squadra'])); ?>" method="post">
					<h4 class="no-margin">Carica il tuo logo:</h4>
							<input class="upload" name="userfile" type="file" />
							<input type="submit" class="submit" value="Invia file" />
			</form>
			<div id="accordion" class="ui-accordion-container">
				<a class="ui-accordion-link" href="#">Dati</a>
				<?php endif;?>
				<div>
					<table class="column last"  cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<th>Proprietario:</th>
								<td><?php echo $this->squadradett['nomeProp'] . " " . $this->squadradett['cognome'] ?></td>
							</tr>
							<tr>
								<th>Username:</th>
								<td><?php echo $this->squadradett['username'] ?></td>
							</tr>
							<tr>
								<th>E-mail:</th>
								<td><?php echo $this->squadradett['mail'] ?></td>
							</tr>
							<tr>
								<th>Media punti:</th>
								<td><?php echo $this->media ?></td>
							</tr>
							<tr>
								<th>Punti min:</th>
								<td><?php echo $this->min ?></td>
							</tr>
							<tr>
								<th>Punti max:</th>
								<td><?php echo $this->max ?></td>
							</tr>
						</tbody>
					</table>
					<?php if(strcasecmp($this->squadradett['username'], $_SESSION['userid']) == 0): ?>
					<p id="mex">Se vuoi modificare le tue informazioni personali come mail, nome, password<?php if(GIORNATA <= 2): ?>. Fino alla seconda giornata imposta quì anche il nome della tua squadra<?php endif; ?></p>
				</div>
				<a class="ui-accordion-link" href="#">Clicca qui</a>
				<div class="no-margin">
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
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
<?php if(strcasecmp($this->squadradett['username'], $_SESSION['userid']) == 0): ?>
<script type="text/javascript" >
$(document).ready(function(){
	$('#accordion').accordion({ autoHeight: false });
});
</script>
<?php endif; ?>
<?php else: ?>
<div class="titolo-pagina">
	<div class="column logo-tit">
		<img src="<?php echo IMGSURL.'rose-big.png'; ?>" alt="Logo Squadre" />
	</div>
	<h2 class="column">Le squadre</h2>
</div>
<div id="squadre" class="main-content">
<?php foreach($this->elencosquadre as $key => $val): ?>
	<div class="box-squadra column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<?php if(file_exists(UPLOADDIR. $val['idUtente'].'-small.jpg')): ?>
				<a rel="group" title="<?php echo $val['nome']; ?>" class="fancybox" href="<?php echo UPLOADIMGURL.$val['idUtente'].'-original.jpg'; ?>" >
				<?php 
				$image = imagecreatefromjpeg(UPLOADDIR.$val['idUtente'].'-med.jpg');
				$width = imagesx ($image); 
				imagedestroy($image);
				if($width > 101)
					$appo = floor(($width - 100) / 2);
				?>
					<img class="logo" alt="<?php echo $val['idUtente']; ?>" src="<?php echo UPLOADIMGURL. $val['idUtente'].'-med.jpg'; ?>" title="Clicca per vedere l'immagine ingrandita"<?php if(isset($appo)) echo ' style="margin-left:-'.$appo.'px"'; ?> />
					<img class="logo reflex" alt="<?php echo $val['idUtente']; ?>" src="<?php echo UPLOADIMGURL. $val['idUtente'].'-med-reflex.jpg'; ?>" title="Clicca per vedere l'immagine ingrandita"<?php if(isset($appo)) echo ' style="margin-left:-'.$appo.'px"'; ?>  />
				</a>
			<?php else: ?>
				<img class="logo column last" alt="<?php echo $val['idUtente']; ?>" src="<?php echo IMGSURL.'no-foto.png'; ?>" title="<?php echo $val['nome']; ?>" />
			<?php endif; ?>
			<h3><a href="<?php echo $this->linksObj->getLink('rosa',array('squadra'=>$val['idUtente'])); ?>" title="Maggiori informazioni"><?php echo $val['nome']; ?></a></h3>	
			<div>Proprietario: <?php echo $val['username'] ?></div>
			<div>Pos. in classifica: <?php echo $this->posizioni[$val['idUtente']] ?></div>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
<?php unset($appo); endforeach; ?>
</div>
	<?php if($_SESSION['logged'] == TRUE): ?>
	<div id="squadradett" class="column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<?php else: ?>
		<div class="right">&nbsp;</div>
	<?php endif; ?>
<?php endif; ?>
<script type="text/javascript">
	$(document).ready(function() { 
		$(".fancybox").fancybox({
			'zoomSpeedIn': 500,
			'zoomSpeedOut' : 500,
			'imageScale' : true,
			'zoomOpacity' : true,
			'overlayShow' : true,
			'overlayOpacity' : 0.6,
			'centerOnScroll' : true,
			'padding' : 0,
			'hideOnContentClick' : false
			})
	}); 
</script>
