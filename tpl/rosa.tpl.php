<?php $r='Por.'; ?>
<?php if($this->squadra != NULL && $this->squadra > 0 && $this->squadra < 9): ?>
<div class="titolo-pagina">
	<div class="column logo-tit">
		<?php if(file_exists(UPLOADDIR. $this->squadradett['IdSquadra'].'-small.jpg')): ?>
			<a href="<?php echo UPLOADDIR.$this->squadradett['IdSquadra'].'-original.jpg'; ?>" class="lightbox">
				<img alt="<?php echo $this->squadradett['IdSquadra']; ?>" src="<?php echo UPLOADDIR. $this->squadradett['IdSquadra'].'-small.jpg'; ?>" title="Logo <?php echo $this->squadradett['nome']; ?>" />
				<img class="reflex" align="left" alt="<?php echo $this->squadradett['IdSquadra']; ?>" src="<?php echo UPLOADDIR. $this->squadradett['IdSquadra'].'-small-reflex.jpg'; ?>" title="Logo <?php echo $this->squadradett['nome']; ?>" />
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
				<th>Ruolo</th>
				<th>Sq.</th>
				<th>MV</th>
				<th>PG</th>
			</tr>
			<?php foreach($this->giocatori as $key=>$val): ?>
			<tr class="row">
				<td<?php if($val['ruolo'] != $r) echo " class=\"ult\""?>><?php echo $val['nome']; ?></td>
				<td<?php if($val['ruolo'] != $r) echo " class=\"ult\""?>><?php echo $val['ruolo']; ?></td>
				<td<?php if($val['ruolo'] != $r) echo " class=\"ult\""?>><?php echo $val['club']; ?></td>
				<td<?php if($val['ruolo'] != $r) echo " class=\"ult\""?> title="<?php echo $val['votiAll'] ?>"><?php echo $val['voti']; ?></td>
				<td<?php if($val['ruolo'] != $r) echo " class=\"ult\""?>><?php echo $val['partite']; ?></td>
			</tr>
			<?php $r = $val ['ruolo'];  ?>
			<?php endforeach; ?>
			<tr>
				<td class="ult" colspan="3">Media</td>
				<td class="ult" title="<?php echo $this->mediaVotoAll; ?>"><?php echo $this->mediaVoto; ?></td>
				<td class="ult" title="<?php echo $this->mediaPartiteAll; ?>"><?php echo $this->mediaPartite; ?></td>
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
			<div class="messaggio bad column last">
				<img alt="!" src="<?php echo IMGSURL.'attention-bad-big.png'; ?>" title="Attenzione!" />
				<span><?php echo $this->message[1]; ?></span>
			</div>
		<?php elseif($this->message[0] == 0): /* TUTTO OK */?>
			<div class="messaggio good column last">
				<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
				<span><?php echo $this->message[1]; ?></span>
			</div>
		<?php endif; ?>
		<script type="text/javascript">
			$(".messaggio").click(function () {
				$("div.messaggio").fadeOut("slow");
			});
		</script>
	<?php endif; ?>
		<?php if($_SESSION['logged'] == TRUE): ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		<?php endif; ?>
		<?php if(file_exists(UPLOADDIR. $this->squadradett['IdSquadra'].'.jpg')): ?>
			<a href="<?php echo UPLOADDIR. $this->squadradett['IdSquadra'].'-original.jpg'; ?>" class="lightbox">
				<img class="logo" alt="<?php echo $this->squadradett['IdSquadra']; ?>" src="<?php echo UPLOADDIR. $this->squadradett['IdSquadra'].'.jpg'; ?>" title="Logo <?php echo $this->squadradett['nome']; ?>" />
				<img class="logo reflex" alt="<?php echo $this->squadradett['IdSquadra']; ?>" src="<?php echo UPLOADDIR. $this->squadradett['IdSquadra'].'-reflex.jpg'; ?>" title="Logo <?php echo $this->squadradett['nome']; ?>" />
			</a>
		<?php endif; ?>
		<?php if(strcasecmp($this->squadradett['username'], $_SESSION['userid']) == 0): ?>
			<form enctype="multipart/form-data" id="formupload" name="uploadlogo" action="index.php?p=rosa&amp;squadra=<?php echo $_GET['squadra'] ?>" method="post">
					<h4 class="no-margin">Carica il tuo logo:</h4>
							<input class="upload" name="userfile" type="file" accept="image/gif, image/jpeg, image/jpg" />
							<input type="submit" class="submit" value="Invia file" />
			</form>
			<ul id="accordion" class="ui-accordion-container">
				<li><a class="ui-accordion-link" href="#">Dati</a>
				<div class="">
				<?php endif;?>
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
					<p id="mex"><?php if($this->data == 0) echo "Se vuoi modificare le tue informazioni personali come mail, nome, password"; elseif($this->data == 1) echo "Le due password non corrispondono"; elseif($this->data == 2) echo "Dati modificati. Vuoi modificarli di nuovo?";  ?></p>
				</div>
				</li>
				<li><a class="ui-accordion-link" href="#">Clicca qui</a>
				<div class="no-margin" style="display:none;">
					<form id="userdata" action="index.php?p=rosa" name="data" method="post">
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
			</li>
			</ul>
			<?php endif; ?>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
<script type="text/javascript" >
$(window).bind("load",function(){
	$('#accordion').accordion();
});
</script>
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
			<?php if(file_exists(UPLOADDIR. $val[0].'-small.jpg')): ?>
				<a class="lightbox" href="<?php echo UPLOADDIR.$val[0].'-original.jpg'; ?>" >
				<?php 
				$image = imagecreatefromjpeg(UPLOADDIR.$val[0].'-med.jpg');
				$width = imagesx ($image); 
				imagedestroy($image);
				if($width > 101)
					$appo = floor(($width - 100) / 2);
				?>
					<img class="logo" alt="<?php echo $val[0]; ?>" src="<?php echo UPLOADDIR. $val[0].'-med.jpg'; ?>" title="Clicca per vedere l'immagine ingrandita"<?php if(isset($appo)) echo ' style="margin-left:-'.$appo.'px"'; ?> />
					<img class="logo reflex" alt="<?php echo $val[0]; ?>" src="<?php echo UPLOADDIR. $val[0].'-med-reflex.jpg'; ?>" title="Clicca per vedere l'immagine ingrandita"<?php if(isset($appo)) echo ' style="margin-left:-'.$appo.'"px'; ?>  />
				</a>
			<?php else: ?>
				<img class="logo column last" alt="<?php echo $val[0]; ?>" src="<?php echo IMGSURL.'no-foto.png'; ?>" title="<?php echo $val[1]; ?>" />
			<?php endif; ?>
			<h3><a href="index.php?p=rosa&amp;squadra=<?php echo $val[0]; ?>" title="Maggiori informazioni"><?php echo $val[1]; ?></a></h3>	
			<div>Proprietario: <?php echo $val[5] ?></div>
			<div>Pos. in classifica: <?php echo $this->posizioni[$val[0]] ?></div>
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
$(function() {
	$('a.lightbox').lightBox({
		imageLoading: '<?php echo IMGSURL; ?>lightbox-ico-loading.gif',
		imageBtnClose: '<?php echo IMGSURL; ?>lightbox-btn-close.png',
		imageBtnPrev: '<?php echo IMGSURL; ?>lightbox-btn-prev.png',
		imageBtnNext: '<?php echo IMGSURL; ?>lightbox-btn-next.png',
		imageBlank: '<?php echo IMGSURL; ?>lightbox-blank.png',
		fixedNavigation: false,
		txtImage: 'Immagine',
		txtOf: 'di'
	}); // Select all links with lightbox class
});
</script>
