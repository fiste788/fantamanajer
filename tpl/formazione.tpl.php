<?php $j =0; $k = 0; ?>
<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'formazione-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Formazione</h2>
</div>
<div id="formazione" class="main-content">
	<?php if($this->timeout): ?>
		<h3>Giornata <?php echo $this->giornata; ?></h3>
		<?php if(isset($this->mod) && $this->mod != NULL): ?>
				<div>
					<img alt="modulo" id="img-modulo" title="<?php echo substr($this->mod,2) ?>" src="<?php echo IMGSURL.$this->mod.'.png' ?>" />
				</div>
				<form id="form-formazione" name="formazione" action="<?php echo $this->linksObj->getLink('formazione'); ?>" method="post">
					<fieldset id="titolari">
					<input type="hidden" name="mod" value="<?php echo $this->mod; ?>">
					<h3 class="center">Titolare</h3>
				<?php /*CONTROLLO SE IL MODULO È SETTATO E FACCIO IL FOR CHE STAMPA LE SELECT*/
				/*INDICE RUOLI:
				1 - PORTIERI
				2 - DIFESORI
				3 - CENTROCAMPISTI
				4 - ATTACCANTI */
				$ruo = array('Portiere','Difensori','Centrocampisti','Attaccanti');
				$elencocap = array('C','VC','VVC'); ?>
				<?php foreach($this->giocatori as $key => $val): ?>
					<h4 class="bold no-margin"><?php echo ucfirst($ruo[$j]); ?></h4><hr />
					<?php for($i = 0; $i < $this->modulo[$j] ; $i++): ?>
						<select name="<?php echo substr($ruo[$j],0,3). '-' . $i ; ?>">
							<option></option>
							<?php if(isset($this->titolari[$k])) $every=$this->titolari[$k];
							foreach($val as $key3=>$val3): 
							$selected = FALSE; 
							if(isset($_POST[substr($ruo[$j],0,3). '-' . $i]) && $_POST[substr($ruo[$j],0,3). '-' . $i] == $val3['idGioc'])
							{
								$selected=TRUE;
								$selezionato = $val3['idGioc'];
								unset($every);
							}
							elseif(isset($every) && $every == $val3['idGioc'])
							{
								$selected = TRUE;
								$selezionato = $val3['idGioc'];
								unset($every);
							}
							?><option value="<?php echo $val3['idGioc']; ?>" <?php if($selected) echo ' selected="selected"'; ?>><?php echo $val3['cognome'] . " " . $val3['nome']; ?></option>
						  	<?php  endforeach; ?>
						</select>
						<?php if($j == 0 || $j ==1 ): /*SE È UN DIFENSORE O UN PORTIERE VISULIZZO LA SELECT PER IL CAPITANO */ ?>
						<select class="cap" name="<?php $nome=substr($ruo[$j],0,3).'-'.$i.'-cap'; echo $nome; ?>">
							<option></option>
							<?php foreach ($elencocap as $elem):
							$selected = FALSE; 
							if(isset($_POST[$nome]) && $_POST[$nome] == $elem)
								$selected = TRUE;
							elseif(!empty($this->cap))
							{
								if(array_search($selezionato,$this->cap) == $elem)
								{
									$selected = TRUE;
									unset($this->cap[$nome]);
								}
							}
							?><option<?php if($selected) echo ' selected="selected"'; ?>><?php echo $elem; ?></option>
							<?php endforeach;?>
						</select>
						<?php endif; ?>
					<?php $k++; endfor; ?>
				<?php $j++; endforeach; ?>
				</fieldset>
				<fieldset id="panchinari">
					<h3 class="center">Panchina</h3>
					<h4 class="bold no-margin">Giocatori</h4><hr />
					<?php for( $i = 0 ; $i < 7 ; $i++): ?>
					<select name="<?php echo 'panch-'. $i; ?>">
					<option></option>
						<?php if(isset($this->panchinari[$i])) $every = $this->panchinari[$i]; 
							for($j = 0 ; $j < count($ruo) ; $j++): ?>
							<optgroup label="<?php echo $ruo[$j] ?>">
								<?php foreach($this->giocatori[substr($ruo[$j],0,1)] as $key3=>$val3): 
								$selected = FALSE; 
								if(isset($_POST["panch-". $i]) && $_POST["panch-". $i] == $val3['idGioc'])
								{
									$selected = TRUE;
									unset($every);
								}
								if(isset($every) && $every == $val3['idGioc'])
								{
									$selected= TRUE;
									unset($every);
								}
								?><option value="<?php echo $val3['idGioc']; ?>"<?php if($selected) echo ' selected="selected"'; ?>><?php  echo $val3['cognome'] . " " . $val3['nome'];?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endfor; ?>
					</select>
					<?php unset($ogni); endfor; ?>
					<div class="div-submit">
						<input class="submit dark" type="submit" name="button" value="Invia" />
						<input class="submit dark" type="reset" value="Torna indietro" />
					</div>
					</fieldset>
				</form>
			<?php endif; ?>
	<?php endif; ?>
</div>
<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
		<?php if(!$this->timeout): //NON PIÙ NECESSARIO QUESTO IF PER L'HEADER LOCATION NELLA CODE' ?>
		<div id="messaggio" class="messaggio neut column last" >
			<img alt="!" src="<?php echo IMGSURL.'attention-big.png'; ?>" />
			<span>Non puoi effettuare operazioni in questo momento.Aspetta la fine delle partite</span>
		</div>
		<?php endif; ?>
		<?php if(isset($this->message) && $this->message[0] == 0): ?>
		<div id="messaggio" class="messaggio good column last">
			<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
			<span><?php echo $this->message[1]; ?></span>
		</div>
		<?php elseif(isset($this->message) && $this->message[0] == 1): ?>
		<div id="messaggio" class="messaggio bad column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-bad-big.png'; ?>" />
			<span><?php echo $this->message[1]; ?></span>
		</div>
		<?php elseif($this->issetForm != FALSE):  ?>
			<div id="messaggio" class="messaggio neut column last">
				<img alt="!" src="<?php echo IMGSURL.'attention-big.png'; ?>" title="Attenzione!" />
				<span>Hai già impostato la formazione. Se la rinvii quella vecchia verrà sovrascritta</span>
			</div>
		<?php endif; ?>
		<?php if($this->issetForm != FALSE || isset($this->message)): ?> 
		<script type="text/javascript">
		$(document).ready(function() {$('#messaggio').show('pulsate',{times: 3 }); });
		$("#messaggio").click(function () {
			$("div#messaggio").fadeOut("slow");
		});
		</script>
		<?php endif; ?>
		<?php if($_SESSION['logged'] == TRUE): ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		<?php endif; ?>
		<form class="column last" name="form_modulo" action="<?php echo $this->linksObj->getLink('formazione'); ?>" method="post">
			<fieldset id="modulo" class="no-margin fieldset">
				<h3 class="no-margin">Seleziona il modulo:</h3>
				<select name="mod" onchange="document.form_modulo.submit();">
					<?php if(!isset($this->mod)): ?><option></option><?php endif; ?>
					<option value="1-4-4-2" <?php if ($this->mod == '1-4-4-2') echo "selected=\"selected\""?>>4-4-2</option>
					<option value="1-3-5-2" <?php if ($this->mod == '1-3-5-2') echo "selected=\"selected\""?>>3-5-2</option>
					<option value="1-3-4-3" <?php if ($this->mod == '1-3-4-3') echo "selected=\"selected\""?>>3-4-3</option>
					<option value="1-4-5-1" <?php if ($this->mod == '1-4-5-1') echo "selected=\"selected\""?>>4-5-1</option>
					<option value="1-4-3-3" <?php if ($this->mod == '1-4-3-3') echo "selected=\"selected\""?>>4-3-3</option>
					<option value="1-5-4-1" <?php if ($this->mod == '1-5-4-1') echo "selected=\"selected\""?>>5-4-1</option>
					<option value="1-5-3-2" <?php if ($this->mod == '1-5-3-2') echo "selected=\"selected\""?>>5-3-2</option>
				</select>
			</fieldset>
		</form>
		<form class="right last" name="formazione_other" action="<?php echo $this->linksObj->getLink('altreFormazioni'); ?>" method="post">
			<fieldset class="no-margin fieldset">
			  <input type="hidden" name="p" value="formazioniAll" />
				<h3 class="no-margin">Guarda le altre formazioni</h3>
				<?php if(empty($this->formazioniImpostate)): ?>
					<select name="squadra" disabled="disabled">
						<option>Nessuna form. impostata</option>
				<?php else:?>
					<select name="squadra" onchange="document.formazione_other.submit();">
						<option value="<?php echo $_SESSION['idSquadra']; ?>"></option>
					<?php foreach($this->formazioniImpostate as $key => $val): ?>
						<option <?php if($this->squadra == $val['idUtente']) echo "selected=\"selected\"" ?> value="<?php echo $val['idUtente']?>"><?php echo $val['nome']?></option>
					<?php endforeach;?>
				<?php endif; ?>
				</select>
			</fieldset>
			<fieldset class="no-margin fieldset max-large">
				<h3 class="no-margin">Guarda la formazione della giornata</h3>
					<select name="giorn" onchange="document.formazione_other.submit();">
						<?php for($j = GIORNATA ; $j  > 0 ; $j--): ?>
							<option <?php if(GIORNATA == $j) echo "selected=\"selected\"" ?>><?php echo $j; ?></option>
						<?php endfor; ?>
				</select>
			</fieldset>
		</form>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
