<?php $j = 0; ?>
<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'other-big.png'; ?>" alt="Logo Squadre" />
	</div>
	<h2 class="column">Crea squadra</h2>
</div>
<div id="creaSquadre" class="main-content">
	<form name="creaSquadra" action="<?php echo $this->linksObj->getLink('creaSquadra'); ?>" method="post">
		<fieldset id="dettaglioSquadra" class="column last no-margin">
			<h3>Informazioni generali</h3>
			<div class="formbox">
				<label for="nomeSquadra">Nome della squadra:</label>
				<input class="text" id="nomeSquadra" name="nomeSquadra" type="text" maxlength="40" <?php if(isset($_POST['nomeSquadra'])) echo 'value="'. $_POST['nomeSquadra'] .'"'; ?> />
			</div>
			<div class="formbox">
				<label for="username">Username:</label>
				<input class="text" id="username" name="usernamenew" type="text" maxlength="15" <?php if(isset($_POST['usernamenew'])) echo 'value="'. $_POST['usernamenew'] .'"'; ?> />
			</div>
			<div class="formbox">
				<label for="email">Email:</label>
				<input class="text" id="email" name="email" type="text" maxlength="30" <?php if(isset($_POST['email'])) echo 'value="'. $_POST['email'] .'"'; ?> />
			</div>
			<div class="formbox">
				<label for="amministratore">Amministratore?</label>
				<input class="checkbox" id="amministratore" name="amministratore" type="checkbox" <?php if(isset($_POST['amministratore'])) echo 'checked="checked"'; ?> />
			</div>
		</fieldset>
		<fieldset id="panchinari">
			<h4 class="bold no-margin">Portieri</h4>
			<hr />
			<?php for($i = 0;$i < 3; $i++): ?>
				<select name="giocatore-<?php echo $j ?>">
					<option></option>
					<?php foreach($this->portieri as $key=>$val): ?>
						<option <?php if(isset($_POST['giocatore-'.$j]) && $_POST['giocatore-'.$j] == $val['IdGioc']) echo 'selected="selected"'; ?> value="<?php echo $val['IdGioc'] ?>"><?php echo $val['Cognome'] ." ". $val['Nome'] ?></option>
					<?php endforeach; ?>
				</select>
			<?php $j++; endfor; ?>
			<h4 class="bold no-margin">Difensori</h4>
			<hr />
			<?php for($i = 0;$i < 8; $i++): ?>
				<select name="giocatore-<?php echo $j ?>">
					<option></option>
					<?php foreach($this->difensori as $key=>$val): ?>
						<option <?php if(isset($_POST['giocatore-'.$j]) && $_POST['giocatore-'.$j] == $val['IdGioc']) echo 'selected="selected"'; ?> value="<?php echo $val['IdGioc'] ?>"><?php echo $val['Cognome'] ." ". $val['Nome'] ?></option>
					<?php endforeach; ?>
				</select>
			<?php $j++; endfor; ?>
			<h4 class="bold no-margin">Centrocampisti</h4>
			<hr />
			<?php for($i = 0;$i < 8; $i++): ?>
				<select name="giocatore-<?php echo $j ?>">
					<option></option>
					<?php foreach($this->centrocampisti as $key=>$val): ?>
						<option <?php if(isset($_POST['giocatore-'.$j]) && $_POST['giocatore-'.$j] == $val['IdGioc']) echo 'selected="selected"'; ?> value="<?php echo $val['IdGioc'] ?>"><?php echo $val['Cognome'] ." ". $val['Nome'] ?></option>
					<?php endforeach; ?>
				</select>
			<?php $j++; endfor; ?>
			<h4 class="bold no-margin">Attaccanti</h4>
			<hr />
			<?php for($i = 0;$i < 6; $i++): ?>
				<select name="giocatore-<?php echo $j ?>">
					<option></option>
					<?php foreach($this->attaccanti as $key=>$val): ?>
						<option <?php if(isset($_POST['giocatore-'.$j]) && $_POST['giocatore-'.$j] == $val['IdGioc']) echo 'selected="selected"'; ?> value="<?php echo $val['IdGioc'] ?>"><?php echo $val['Cognome'] ." ". $val['Nome'] ?></option>
					<?php endforeach; ?>
				</select>
			<?php $j++; endfor; ?>
		</fieldset>
		<fieldset class="column div-submit">
			<input type="submit" class="submit dark" value="Crea" />
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
</div>
	<?php if($_SESSION['logged'] == TRUE): ?>
	<div id="squadradett" class="column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
		<?php if(isset($this->messaggio) && $this->messaggio[0] == 0): ?>
		<div class="messaggio good column last">
			<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
			<span><?php echo $this->messaggio[1]; ?></span>
		</div>
		<?php elseif(isset($this->messaggio) && $this->messaggio[0] == 1): ?>
		<div class="messaggio bad column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-bad-big.png'; ?>" />
			<span><?php echo $this->messaggio[1]; ?></span>
		</div>
		<?php elseif(isset($this->messaggio) && $this->messaggio[0] == 2): ?>
		<div class="messaggio neut column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-big.png'; ?>" />
			<span><?php echo $this->messaggio[1]; ?></span>
		</div>
		<?php endif; ?>
			<?php if(isset($this->messaggio)): ?>
			<script type="text/javascript">
			$(document).ready(function() {$('.messaggio').show('pulsate',{times: 3 }); });
			$(".messaggio").click(function () {
				$("div.messaggio").fadeOut("slow");
			});
			</script>
			<?php endif; ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<?php endif; ?>
