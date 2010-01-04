<?php if(isset($this->formImp) && !$this->formImp): ?>	
	<h3>Giornata <?php GIORNATA; ?></h3>
	<?php if(isset($this->squadra) && $this->squadra != NULL && isset($this->mod) && $this->mod != NULL && isset($this->giornata) && $this->giornata != NULL): ?>	
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
			<?php foreach($this->giocatori as $key => $val): ?>
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