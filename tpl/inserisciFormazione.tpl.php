<?php $j =0; $k = 0; ?>
<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'formazione-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Formazione</h2>
</div>
<div id="formazione" class="main-content">
	<?php if(isset($this->formImp) && !$this->formImp): ?>	
		<h3>Giornata <?php echo $this->giornata; ?></h3>
		<?php if(isset($this->mod) && $this->mod != NULL): ?>	
				<div>
					<img alt="modulo" id="img-modulo" title="<?php echo substr($this->mod,2) ?>" src="<?php echo IMGSURL.$this->mod.'.png' ?>" />
				</div>
				<form id="form-formazione" name="formazione" action="<?php echo $this->linksObj->getLink('inserisciFormazione'); ?>" method="post">
					<input type="hidden" name="lega" value="<?php echo $this->lega; ?>" />
					<input type="hidden" name="squad" value="<?php echo $this->squadra; ?>" />
					<input type="hidden" name="giorn" value="<?php echo $this->giornata; ?>" />
					<input type="hidden" name="mod" value="<?php echo $this->mod; ?>" />
					<fieldset id="titolari">
					<h3 class="center">Titolare</h3>
				<?php /*CONTROLLO SE IL MODULO È SETTATO E FACCIO IL FOR CHE STAMPA LE SELECT*/
				/*INDICE RUOLI:
				1 - PORTIERI
				2 - DIFESORI
				3 - CENTROCAMPISTI
				4 - ATTACCANTI */
				$ruo = array('Portiere','Difensori','Centrocampisti','Attaccanti');
	    		$elencocap = array('C','VC','VVC'); ?>
				<?php foreach($this->giocatori as $key=>$val): ?>
				<h4 class="bold no-margin"><?php echo ucfirst($ruo[$j]); ?></h4><hr />
					<?php for($i = 0; $i < $this->modulo[$j] ; $i++): ?>
						<select name="<?php echo substr($ruo[$j],0,3). '-' . $i ; ?>">
							<option></option>
							<?php foreach($val as $key3=>$val3): ?>
							<option value="<?php echo $val3['idGioc']; ?>" <?php if(isset($_POST[substr($ruo[$j],0,3). '-' . $i]) && $_POST[substr($ruo[$j],0,3). '-' . $i] == $val3['idGioc']) echo ' selected="selected"'; ?>><?php echo $val3['cognome'] . " " . $val3['nome']; ?></option>
						  	<?php  endforeach; ?>
						</select>
						<?php if($j == 0 || $j ==1 ): /*SE È UN DIFENSORE O UN PORTIERE VISULIZZO LA SELECT PER IL CAPITANO */ ?>
						<select class="cap" name="<?php $nome=substr($ruo[$j],0,3).'-'.$i.'-cap'; echo $nome; ?>">
							<option></option>
							<?php foreach ($elencocap as $elem): ?>
							<option<?php if(isset($_POST[$nome]) && $_POST[$nome] == $elem) echo ' selected="selected"'; ?>><?php echo $elem; ?></option>
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
				      	<?php if(isset($this->panchinari[$i])) $ogni=$this->panchinari[$i]; $flag= 0;
				      		for($j = 0 ; $j < count($ruo) ; $j++): ?>
							<optgroup label="<?php echo $ruo[$j] ?>">
								<?php foreach($this->giocatori[substr($ruo[$j],0,1)] as $key3=>$val3): ?>
								<option value="<?php echo $val3['idGioc']; ?>"<?php if(isset($_POST["panch-". $i]) && $_POST["panch-". $i] == $val3['idGioc']) echo ' selected="selected"'; ?>><?php  echo $val3['cognome'] . " " . $val3['nome']; ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endfor; ?>
					</select>
					<?php unset($ogni); endfor; ?>
					<div class="div-submit">
						<input name="button" class="submit dark" type="submit" value="Invia" />
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
		<?php if(isset($_SESSION['message']) && $_SESSION['message'][0] == 1): ?>
		<div class="messaggio bad column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-bad-big.png'; ?>" />
			<span><?php echo $_SESSION['message'][1]; ?></span>
		</div>
		<script type="text/javascript">
		$(document).ready(function() {$('.messaggio').show('pulsate',{times: 3 }); });
		$(".messaggio").click(function () {
			$("div.messaggio").fadeOut("slow");
		});
		</script>
		<?php unset($_SESSION['message']); ?>
		<?php endif; ?>
		<?php if($_SESSION['logged'] == TRUE): ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		<?php endif; ?>
		<form class="column last" name="datiFormazione" action="<?php echo $this->linksObj->getLink('inserisciFormazione'); ?>" method="post">
			<?php if($_SESSION['usertype'] == 'superadmin'): ?>
			<fieldset class="no-margin fieldset max-large">
				<h3 class="no-margin">Seleziona la lega:</h3>
				<input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
				<select name="lega" onchange="document.datiFormazione.submit();">
					<option></option>
					<?php foreach($this->elencoleghe as $key=>$val): ?>
						<option <?php if($this->lega == $val['idLega']) echo "selected=\"selected\"" ?> value="<?php echo $val['idLega']?>"><?php echo $val['nomeLega']?></option>
					<?php endforeach ?>
				</select>
			</fieldset>
			<?php endif; ?>
			<fieldset class="no-margin fieldset max-large">
				<h3 class="no-margin">Seleziona la giornata:</h3>
				<select name="giorn" onchange="document.datiFormazione.submit();">
					<option></option>
					<?php for($i = GIORNATA ; $i > 0 ; $i--): ?>
						<option <?php if($this->giornata == $i) echo "selected=\"selected\"" ?> value="<?php echo $i ?>"><?php echo $i ?></option>
					<?php endfor; ?>
				</select>
			</fieldset>
			<fieldset class="no-margin fieldset max-large">
				<h3 class="no-margin">Seleziona la squadra:</h3>
				<select name="squad" onchange="document.datiFormazione.submit();">
					<option></option>
					<?php foreach($this->elencosquadre as $key=>$val): ?>
						<option <?php if($this->squadra == $val['idUtente']) echo "selected=\"selected\"" ?> value="<?php echo $val['idUtente']?>"><?php echo $val['nome']?></option>
					<?php endforeach ?>
				</select>
			</fieldset>
			<fieldset id="modulo" class="no-margin fieldset">
				<h3 class="no-margin">Seleziona il modulo:</h3>
				<select name="mod" onchange="document.datiFormazione.submit();">
					<option></option>
					<?php if(isset($_POST['squad']) && isset($this->lega) && $this->lega != NULL && isset($this->formImp) && $this->formImp != TRUE): ?>
					<option value="1-4-4-2" <?php if ($this->mod == '1-4-4-2') echo "selected=\"selected\""?>>4-4-2</option>
					<option value="1-3-5-2" <?php if ($this->mod == '1-3-5-2') echo "selected=\"selected\""?>>3-5-2</option>
					<option value="1-3-4-3" <?php if ($this->mod == '1-3-4-3') echo "selected=\"selected\""?>>3-4-3</option>
					<option value="1-4-5-1" <?php if ($this->mod == '1-4-5-1') echo "selected=\"selected\""?>>4-5-1</option>
					<option value="1-4-3-3" <?php if ($this->mod == '1-4-3-3') echo "selected=\"selected\""?>>4-3-3</option>
					<option value="1-5-4-1" <?php if ($this->mod == '1-5-4-1') echo "selected=\"selected\""?>>5-4-1</option>
					<option value="1-5-3-2" <?php if ($this->mod == '1-5-3-2') echo "selected=\"selected\""?>>5-3-2</option>
					<?php endif; ?>
				</select>
			</fieldset>
		</form>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
