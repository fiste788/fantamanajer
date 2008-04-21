<?php $j=0; ?>
<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'formazione-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Altre formazioni</h2>
</div>
<div id="formazione" class="main-content">
	<h3>Giornata <?php echo $this->giornata; ?></h3>
	<?php if($this->formazione != FALSE): ?>
		<img alt="modulo" id="img-modulo" title="<?php echo substr($this->modulo,2) ?>" src="<?php echo IMGSURL.$this->modulo.'.png' ?>" />
	<?php $ruo = array(0 => 'Portiere',1 => 'Difensori',2 => 'Centrocampisti',3 => 'Attaccanti'); ?>
		<form id="form-formazione" name="formazione" action="index.php" method="post">		
			<fieldset id="titolari">
				<h3 class="center">Titolare</h3>	
				<?php /*CONTROLLO SE IL MODULO È SETTATO E FACCIO IL FOR CHE STAMPA LE SELECT*/
				/*INDICE RUOLI: 
				1 - PORTIERI
				2 - DIFESORI
				3 - CENTROCAMPISTI
				4 - ATTACCANTI */
				$ruo = array('Portiere','Difensori','Centrocampisti','Attaccanti');
        $elencocap=array('C','VC','VVC'); ?>
				<?php foreach($this->giocatori as $key=>$val): ?>
					<h4 class="bold no-margin"><?php echo ucfirst($ruo[$j]); ?></h4><hr/>
					<?php for($i = 0; $i < $this->mod[$j] ; $i++): ?>
						<select disabled="disabled" name="<?php echo substr($ruo[$j],0,3). '-' . $i ; ?>">
							<option></option>
							<?php $every=$this->titolari[0];foreach($val as $key3=>$val3): ?>
							  <option value="<?php echo $val3[1];?>"
							    <?
								if($every == $val3[1])
								{
								    array_shift($this->titolari);
								    echo "selected=\"selected\""; 
								}
							    ?>
							    >
								  <?php  
								    echo $val3[0] . " " . $val3[2];
								  ?>
							    </option>
						  	<?php endforeach; ?>
						</select>
						<?php if($j == 0 || $j ==1 ): /*SE È UN DIFENSORE O UN PORTIERE VISULIZZO LA SELECT PER IL CAPITANO */ ?> 
							<select disabled="disabled" class="cap" name="<?php $nome=substr($ruo[$j],0,3).'-'.$i.'-cap'; echo $nome; ?>">
								<option>
								</option>
								<?php foreach ($elencocap as $elem):?>
								<option <?php 
								    if(!empty($this->cap))
								    {
								    if(array_key_exists($nome,$this->cap))
								    {
									if($this->cap[$nome]==$elem)
									{
									  echo "selected=\"selected\"";
									  unset($this->cap[$nome]);
									}                    
								    }
								    }                  
								    ?>><?php echo $elem;?></option>
								<?php endforeach;?>
							</select>
						<?php endif; ?>
					<?php endfor; ?>
				<?php $j++;endforeach; ?>
				</fieldset>
				<fieldset id="panchinari">
					<h3 class="center">Panchina</h3>
					<h4 class="bold no-margin">Giocatori</h4><hr/>
					<?php for( $i = 0 ; $i < 7 ; $i++): ?>
					<select disabled="disabled" name="panch-<?php echo $i; ?>">
					<option></option>
				      	<?php $ogni=$this->panchinari[0];
				      	for($j = 0 ; $j < count($ruo) ; $j++):?>
				      		<optgroup label="<?php echo $ruo[$j] ?>">
				     				<?php foreach($this->giocatori[substr($ruo[$j],0,1)] as $key3=>$val3): ?>
									<option value="<?php echo $val3[1]; ?>"
									<?php
									  if(isset($ogni) && $ogni == $val3[1])
									  {
									    array_shift($this->panchinari);
									    echo "selected=\"selected\""; 
									  }
									?>
									><?php  echo $val3[0] . " " . $val3[2];?></option>
								<?php endforeach; ?>
				      		</optgroup>   
				      	<?php endfor; ?>    
					</select> 
					<?php unset($ogni); endfor;?>
				</fieldset>
			</form>
		<?php endif; ?>
</div>
<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
	<?php if(!$this->formazione): ?>
	<div class="messaggio neut column last" >
		<img src="<?php echo IMGSURL.'attention.big.png'; ?>" />
		<span>La formazione non è stata impostata</span>
	</div>
	<?php endif; ?>
	<?php if($_SESSION['logged'] == TRUE): ?>
		<?php require (TPLDIR.'operazioni.tpl.php'); ?>
	<?php endif; ?>
	<?php if(isset($this->modulo)): ?>
	<form class="column last" name="form-modulo" action="index.php" method="post">
		<fieldset id="modulo" class="no-margin fieldset">
			<h3 class="no-margin">Seleziona il modulo:</h3>
			<select disabled="disabled" name="mod">
				<option value="1-4-4-2" <?php if ($this->modulo == '1-4-4-2') echo "selected=\"selected\""?>>4-4-2</option>
				<option value="1-3-5-2" <?php if ($this->modulo == '1-3-5-2') echo "selected=\"selected\""?>>3-5-2</option>
				<option value="1-3-4-3" <?php if ($this->modulo == '1-3-4-3') echo "selected=\"selected\""?>>3-4-3</option>
				<option value="1-4-5-1" <?php if ($this->modulo == '1-4-5-1') echo "selected=\"selected\""?>>4-5-1</option>
				<option value="1-4-3-3" <?php if ($this->modulo == '1-4-3-3') echo "selected=\"selected\""?>>4-3-3</option>
				<option value="1-5-4-1" <?php if ($this->modulo == '1-5-4-1') echo "selected=\"selected\""?>>5-4-1</option>
				<option value="1-5-3-2" <?php if ($this->modulo == '1-5-3-2') echo "selected=\"selected\""?>>5-3-2</option>
			</select>
		</fieldset>
	</form>
	<?php endif; ?>
	<form class="right last" name="formazione_other" action="index.php?p=formazioniAll" method="post">
		<fieldset class="no-margin fieldset">
			<h3 class="no-margin">Guarda le altre formazioni</h3>
			<?php if(empty($this->formazioniImpostate)): ?>
				<select name="squadra" disabled="disabled">
					<option>Nessuna form. impostata</option>
			<?php else:?>
				<select name="squadra" onchange="document.formazione_other.submit();">
					<option></option>
				<?php foreach($this->formazioniImpostate as $key=>$val): ?>
					<option <?php if($this->squadra == $val[0]) echo "selected=\"selected\"" ?> value="<?php echo $val[0]?>"><?php echo $val[1]?></option>
				<?php endforeach; ?>
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
