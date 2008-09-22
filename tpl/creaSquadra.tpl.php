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
				<input class="text" id="nomeSquadra" name="nomeSquadra" type="text" maxlength="40" />
			</div>
			<div class="formbox">
				<label for="username">Username:</label>
				<input class="text" id="username" name="usernamenew" type="text" maxlength="15" />
			</div>
			<div class="formbox">
				<label for="email">Email:</label>
				<input class="text" id="email" name="email" type="text" maxlength="30" />
			</div>
			<div class="formbox">
				<label for="amministratore">Amministratore?</label>
				<input class="checkbox" id="amministratore" name="amministratore" type="checkbox" />
			</div>
		</fieldset>
		<fieldset id="panchinari">
			<h4 class="bold no-margin">Portieri</h4>
			<hr />
			<?php for($i = 0;$i < 3; $i++): ?>
				<select name="portiere-<?php echo $i ?>">
					<option></option>
					<?php foreach($this->portieri as $key=>$val): ?>
						<option value="<?php echo $val[0] ?>"><?php echo $val[1] ." ". $val[2] ?></option>
					<?php endforeach; ?>
				</select>
			<?php endfor; ?>
			<h4 class="bold no-margin">Difensori</h4>
			<hr />
			<?php for($i = 0;$i < 8; $i++): ?>
				<select name="difensore-<?php echo $i ?>">
					<option></option>
					<?php foreach($this->difensori as $key=>$val): ?>
						<option value="<?php echo $val[0] ?>"><?php echo $val[1] ." ". $val[2] ?></option>
					<?php endforeach; ?>
				</select>
			<?php endfor; ?>
			<h4 class="bold no-margin">Centrocampisti</h4>
			<hr />
			<?php for($i = 0;$i < 8; $i++): ?>
				<select name="centrocampista-<?php echo $i ?>">
					<option></option>
					<?php foreach($this->centrocampisti as $key=>$val): ?>
						<option value="<?php echo $val[0] ?>"><?php echo $val[1] ." ". $val[2] ?></option>
					<?php endforeach; ?>
				</select>
			<?php endfor; ?>
			<h4 class="bold no-margin">Attaccanti</h4>
			<hr />
			<?php for($i = 0;$i < 6; $i++): ?>
				<select name="attaccante-<?php echo $i ?>">
					<option></option>
					<?php foreach($this->attaccanti as $key=>$val): ?>
						<option value="<?php echo $val[0] ?>"><?php echo $val[1] ." ". $val[2] ?></option>
					<?php endforeach; ?>
				</select>
			<?php endfor; ?>
		</fieldset>
		<fieldset class="column div-submit">
			<input type="submit" class="submit dark" value="Crea" />
		</fieldset>
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
