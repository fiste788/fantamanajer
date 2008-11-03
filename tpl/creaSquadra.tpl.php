<?php $j = 0; ?>
<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'rose-big.png'; ?>" alt="Logo Squadre" />
	</div>
	<h2 class="column">Crea squadra</h2>
</div>
<div id="creaSquadre" class="main-content">
	<?php if($this->lega != NULL): ?>
	<form class="column" name="creaSquadra" action="<?php echo $this->linksObj->getLink('creaSquadra'); ?>" method="post">
		<fieldset class="column">
			<input type="hidden" name="a" value="<?php if(isset($this->getAction)) echo $this->getAction; ?>" />
			<input type="hidden" name="id" value="<?php if(isset($this->getId)) echo $this->getId; ?>" />
			
			<h3>Informazioni generali</h3>
			<div class="formbox">
				<label for="nomeSquadra">Nome della squadra:</label>
				<input class="text" id="nomeSquadra" name="nome" type="text" maxlength="40" <?php if(isset($this->datiSquadra['nome'])) $nomeSquadra = $this->datiSquadra['nome']; if(isset($_POST['nome'])) $nomeSquadra = $_POST['nome']; if(isset($nomeSquadra)) echo 'value="' . $nomeSquadra . '"'; ?> />
			</div>
			<div class="formbox">
				<label for="username">Username:</label>
				<input class="text" id="username" name="usernamenew" type="text" maxlength="15" <?php if(isset($this->datiSquadra['username'])) $username = $this->datiSquadra['username']; if(isset($_POST['usernamenew'])) $username = $_POST['usernamenew']; if(isset($username)) echo 'value="'. $username .'"'; ?> />
			</div>
			<div class="formbox">
				<label for="email">Email:</label>
				<input class="text" id="mail" name="mail" type="text" maxlength="30" <?php if(isset($this->datiSquadra['mail'])) $mail = $this->datiSquadra['mail']; if(isset($_POST['mail'])) $mail = $_POST['mail']; if(isset($mail))echo 'value="'. $mail .'"'; ?> />
			</div>
			<div class="formbox">
				<label for="amministratore">Amministratore?</label>
				<input class="checkbox" id="amministratore" name="amministratore" type="checkbox" <?php if(isset($this->datiSquadra['amministratore'])) $admin = $this->datiSquadra['amministratore']; if(isset($_POST['amministratore'])) $admin = $_POST['amministratore']; if(isset($admin)) echo 'checked="checked"'; ?> />
			</div>
		</fieldset>
		<fieldset id="panchinari">
			<h4 class="bold no-margin">Portieri</h4>
			<hr />
			<?php for($i = 0;$i < 3; $i++): ?>
				<select name="giocatore-<?php echo $j ?>">
					<option></option>
					<?php if(isset($this->giocatori)): ?>
						<option<?php if(!isset($_POST['giocatore-'.$j])) echo ' selected="selected"' ?> value="<?php echo $this->giocatori[$j]['idGioc']; ?>"><?php echo $this->giocatori[$j]['cognome']. ' ' . $this->giocatori[$j]['nome'] ?></option>
					<?php endif; ?>
					<?php foreach($this->portieri as $key=>$val): ?>
						<option	value="<?php echo $val['idGioc'] ?>"<?php if(isset($_POST['giocatore-'.$j]) && $_POST['giocatore-'.$j] == $val['idGioc']) echo ' selected="selected"'; ?>><?php echo $val['cognome'] ." ". $val['nome'] ?></option>
					<?php endforeach; ?>
				</select>
			<?php $j++; endfor; ?>
			<h4 class="bold no-margin">Difensori</h4>
			<hr />
			<?php for($i = 0;$i < 8; $i++): ?>
				<select name="giocatore-<?php echo $j ?>">
					<option></option>
					<?php if(isset($this->giocatori)): ?>
						<option<?php if(!isset($_POST['giocatore-'.$j])) echo ' selected="selected"' ?> value="<?php echo $this->giocatori[$j]['idGioc']; ?>"><?php echo $this->giocatori[$j]['cognome']. ' ' . $this->giocatori[$j]['nome'] ?></option>
					<?php endif; ?>
					<?php foreach($this->difensori as $key=>$val): ?>
						<option <?php if(isset($_POST['giocatore-'.$j]) && $_POST['giocatore-'.$j] == $val['idGioc']) echo 'selected="selected"'; ?> value="<?php echo $val['idGioc'] ?>"><?php echo $val['cognome'] ." ". $val['nome'] ?></option>
					<?php endforeach; ?>
				</select>
			<?php $j++; endfor; ?>
			<h4 class="bold no-margin">Centrocampisti</h4>
			<hr />
			<?php for($i = 0;$i < 8; $i++): ?>
				<select name="giocatore-<?php echo $j ?>">
					<option></option>
					<?php if(isset($this->giocatori)): ?>
						<option<?php if(!isset($_POST['giocatore-'.$j])) echo ' selected="selected"' ?> value="<?php echo $this->giocatori[$j]['idGioc']; ?>"><?php echo $this->giocatori[$j]['cognome']. ' ' . $this->giocatori[$j]['nome'] ?></option>
					<?php endif; ?>
					<?php foreach($this->centrocampisti as $key=>$val): ?>
						<option <?php if(isset($_POST['giocatore-'.$j]) && $_POST['giocatore-'.$j] == $val['idGioc']) echo 'selected="selected"'; ?> value="<?php echo $val['idGioc'] ?>"><?php echo $val['cognome'] ." ". $val['nome'] ?></option>
					<?php endforeach; ?>
				</select>
			<?php $j++; endfor; ?>
			<h4 class="bold no-margin">Attaccanti</h4>
			<hr />
			<?php for($i = 0;$i < 6; $i++): ?>
				<select name="giocatore-<?php echo $j ?>">
					<option></option>
					<?php if(isset($this->giocatori)): ?>
						<option<?php if(!isset($_POST['giocatore-'.$j])) echo ' selected="selected"' ?> value="<?php echo $this->giocatori[$j]['idGioc']; ?>"><?php echo $this->giocatori[$j]['cognome']. ' ' . $this->giocatori[$j]['nome'] ?></option>
					<?php endif; ?>
					<?php foreach($this->attaccanti as $key=>$val): ?>
						<option <?php if(isset($_POST['giocatore-'.$j]) && $_POST['giocatore-'.$j] == $val['idGioc']) echo 'selected="selected"'; ?> value="<?php echo $val['idGioc'] ?>"><?php echo $val['cognome'] ." ". $val['nome'] ?></option>
					<?php endforeach; ?>
				</select>
			<?php $j++; endfor; ?>
		</fieldset>
		<fieldset class="column div-submit">
			<input type="submit" class="submit dark" value="<?php if(isset($this->getAction) && $this->getAction == 'edit') echo 'Modifica'; else echo 'Crea' ?>" />
		</fieldset>
		<div class="column last">
			<div class="box2-top-sx column last">
			<div class="box2-top-dx column last">
			<div class="box2-bottom-sx column last">
			<div class="box2-bottom-dx column last">
			<div class="box-content column last">
			<h3>Elenco squadre</h3>
			<?php foreach($this->elencosquadre as $key=>$val): ?>
				<div id="elencoSquadre" class="column last">
					<p class="column last"><?php echo $val[1]; ?></p>
					<a class="right last" href="<?php echo $this->linksObj->getLink('creaSquadra',array('a'=>'cancel','id'=>$val[0])); ?>">
						<img src="<?php echo IMGSURL.'cancel.png'; ?>" alt="e" title="Cancella" />
					</a>
					<a class="right last" href="<?php echo $this->linksObj->getLink('creaSquadra',array('a'=>'edit','id'=>$val[0])); ?>">
						<img src="<?php echo IMGSURL.'edit.png'; ?>" alt="m" title="Modifica" />
					</a>
				</div>
			<?php endforeach; ?>
			</div>
			</div>
			</div>
			</div>
			</div>
		</div>
	</form>
	<?php endif; ?>
</div>
	<?php if($_SESSION['logged'] == TRUE): ?>
	<div id="squadradett" class="column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
		<?php if(isset($_SESSION['message']) && $_SESSION['message'][0] == 0): ?>
		<div class="messaggio good column last">
			<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
			<span><?php echo $_SESSION['message'][1]; ?></span>
		</div>
		<?php elseif(isset($_SESSION['message']) && $_SESSION['message'][0] == 1): ?>
		<div class="messaggio bad column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-bad-big.png'; ?>" />
			<span><?php echo $_SESSION['message'][1]; ?></span>
		</div>
		<?php elseif(isset($_SESSION['message']) && $_SESSION['message'][0] == 2): ?>
		<div class="messaggio neut column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-big.png'; ?>" />
			<span><?php echo $_SESSION['message'][1]; ?></span>
		</div>
		<?php endif; ?>
			<?php if(isset($_SESSION['message'])): ?>
			<script type="text/javascript">
			$(document).ready(function() {$('.messaggio').show('pulsate',{times: 3 }); });
			$(".messaggio").click(function () {
				$("div.messaggio").fadeOut("slow");
			});
			</script>
			<?php endif; ?>
			<?php unset($_SESSION['message']) ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
			<?php if($_SESSION['usertype'] == 'superadmin'): ?>
			<form class="column last" name="selezionaLega" action="<?php echo $this->linksObj->getLink('creaSquadra',array('a'=>'new','id'=>'0')); ?>" method="post">
				<fieldset class="no-margin fieldset max-large">
					<h3>Seleziona la lega</h3>
					<select name="lega" onchange="document.selezionaLega.submit();">
						<option></option>
						<?php foreach($this->elencoLeghe as $key=>$val): ?>
							<option<?php if($this->lega == $val['idLega']) echo ' selected="selected"'; ?> value="<?php echo $val['idLega']; ?>"><?php echo $val['nomeLega']; ?></option> 
						<?php endforeach; ?>
					</select>
				</fieldset>
			</form>
			<?php endif; ?>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<?php endif; ?>
