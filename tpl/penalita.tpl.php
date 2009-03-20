<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'penalita-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Inserisci penalità</h2>
</div>
<div id="penalità" class="main-content">
	<?php if(isset($this->classificaDett)): ?>
	<div id="classifica-container" class="column last">
		<div id="classifica-content">
			<?php $i = 1; ?>
			<table class="no-margin" cellpadding="0" cellspacing="0" class="column last" style="width:316px;overflow:hidden;">
				<tbody>
					<tr>
						<th style="width:20px">P.</th>
						<th style="width:180px">Nome</th>
						<th style="width:70px">Punti tot</th>
					</tr>
					<?php foreach($this->classificaDett as $key => $val): ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td class="nowrap"><?php echo $this->squadre[$key]['nome']; ?></td>
						<td><?php echo array_sum($val); ?></td>
					 </tr>
					<?php $i++; $flag = $key; endforeach; ?>
				</tbody>
			</table>
			<div id="tab_classifica" class="column last">
			<?php $i = 1; ?>
			<?php if(key($this->classificaDett[$flag]) != 0): ?>
			<table class="column last" cellpadding="0" cellspacing="0" style="width:<?php echo count($this->classificaDett[$i])*50; ?>px;margin:0;">
				<tbody>
					<tr>
						<?php foreach($this->classificaDett[$flag] as $key => $val): ?>
							<th style="width:35px"><?php echo $key; ?></th>
						<?php endforeach; ?>
					</tr>
					<?php foreach($this->classificaDett as $key => $val): ?>
					<tr>
					<?php foreach($val as $secondKey=>$secondVal): ?>
						<td<?php if(isset($this->penalità[$key][$secondKey])) echo ' title="Penalità: ' . $this->penalità[$key][$secondKey] . ' punti" class="rosso"' ?>>
							<a href="<?php echo $this->linksObj->getLink('dettaglioGiornata',array('giorn'=>$secondKey,'squad'=>$this->squadre[$key][0])); ?>"><?php echo $val[$secondKey]; ?></a>
						</td>
						<?php endforeach; ?>
					</tr>
					<?php $i++; endforeach; ?>
				</tbody>
			</table>
			<?php endif; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php if(isset($this->squadra) && $this->squadra != NULL): ?>
	<form class="column last" id="penalità" name="penalità" action="<?php echo $this->linksObj->getLink('penalita'); ?>" method="post">
		<fieldset class="no-margin">
			<input type="hidden" name="lega" value="<?php echo $this->lega; ?>" />
			<input type="hidden" name="squad" value="<?php echo $this->squadra; ?>" />
			<input type="hidden" name="giorn" value="<?php echo $this->giornata; ?>" />
			<div class="formbox">
				<label for="punti">Punteggio penalità:</label>
				<input type="text" name="punti" id="punti" class="text" <?php if(isset($this->penalitàSquadra) && $this->penalitàSquadra != FALSE) echo ' value="' . $this->penalitàSquadra['punteggio'] . '"' ?> />
			</div>
			<div class="formbox">
				<label for="motivo">Motivazione penalità:</label>
				<input type="text" name="motivo" id="motivo" class="text" <?php if(isset($this->penalitàSquadra) && $this->penalitàSquadra != FALSE) echo ' value="' . $this->penalitàSquadra['penalità'] . '"' ?> />
			</div>
		</fieldset>
		<fieldset class="no-margin">
			<input class="submit dark" type="submit" name="submit" value="OK" />
			<?php if(isset($this->penalitàSquadra) && $this->penalitàSquadra != FALSE): ?>
				<input class="submit dark" type="submit" name="submit" value="Cancella" />
			<?php endif; ?>
		</fieldset>
	</form>
	<?php else: ?>
	Seleziona la squadra
	<?php endif; ?>
</div>
	<?php if($_SESSION['logged'] == TRUE): ?>
	<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
		<?php if(isset($this->messaggio) && $this->messaggio[0] == 0): ?>
		<div id="messaggio" class="messaggio good column last">
			<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
			<span><?php echo $this->messaggio[1]; ?></span>
		</div>
		<?php elseif(isset($this->messaggio) && $this->messaggio[0] == 1): ?>
		<div id="messaggio" class="messaggio bad column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-bad-big.png'; ?>" />
			<span><?php echo $this->messaggio[1]; ?></span>
		</div>
		<?php elseif(isset($this->messaggio) && $this->messaggio[0] == 2): ?>
		<div id="messaggio" class="messaggio neut column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-big.png'; ?>" />
			<span><?php echo $this->messaggio[1]; ?></span>
		</div>
		<?php endif; ?>
		<?php if(isset($this->messaggio)): ?>
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
		<form class="column last" name="trasferimenti" action="<?php echo $this->linksObj->getLink('penalita'); ?>" method="post">
		<?php if($_SESSION['usertype'] == 'superadmin'): ?>
		<fieldset class="no-margin fieldset max-large">
			<h3 class="no-margin">Seleziona la lega:</h3>
			<select name="lega">
				<?php if($this->lega == NULL): ?><option></option><?php endif; ?>
				<?php foreach($this->elencoleghe as $key => $val): ?>
					<option <?php if($this->lega == $val['idLega']) echo "selected=\"selected\"" ?> value="<?php echo $val['idLega']?>"><?php echo $val['nomeLega']?></option>
				<?php endforeach ?>
			</select>
		</fieldset>
		<?php endif; ?>
		<fieldset class="no-margin fieldset max-large">
			<h3 class="no-margin">Seleziona la giornata:</h3>
			<input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
			<select id="giorn" name="giorn">
				<?php if(!isset($this->giornata)): ?><option></option><?php endif; ?>
				<?php for($i = $this->giornate ; $i > 0 ; $i--): ?>
					<option<?php if($this->giornata == $i) echo ' selected="selected"'; ?> value="<?php echo $i ?>"><?php echo $i ?></option>
				<?php endfor; ?>
			</select>
		</fieldset>
		<?php if(isset($this->lega) && $this->lega != NULL && isset($this->giornata) && $this->giornata != NULL): ?>
		<fieldset class="no-margin fieldset max-large">
			<h3 class="no-margin">Seleziona la squadra:</h3>
			<?php if(!$this->elencosquadre): ?>
				<select disabled="disabled" name="squad">
					<option value="NULL">Nessuna squadra presente</option>
			<?php else: ?>
				<select name="squad">
				<?php if($this->squadra == NULL): ?><option></option><?php endif; ?>
				<?php foreach($this->elencosquadre as $key => $val): ?>
					<option <?php if($this->squadra == $val['idUtente']) echo "selected=\"selected\"" ?> value="<?php echo $val['idUtente']?>"><?php echo $val['nome']?></option>
				<?php endforeach ?>
			<?php endif; ?>
			</select>
		</fieldset>
		<?php endif; ?>
		<input type="submit" class="submit" value="OK" />
	</form>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
<?php endif; ?>
