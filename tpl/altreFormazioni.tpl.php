<?php 
$j=0;
$i=0;  ?>
<?php $ruo = array(0 => 'Portiere',1 => 'Difensori',2 => 'Centrocampisti',3 => 'Attaccanti'); ?>
<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'formazione-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Altre formazioni</h2>
</div>
<div id="formazione" class="main-content">
	<h3>Giornata <?php echo $this->getGiornata; ?></h3>
	<?php if($this->formazione != FALSE): ?>
		<img alt="modulo" id="img-modulo" title="<?php echo substr($this->modulo,2) ?>" src="<?php echo IMGSURL.$this->modulo.'.png' ?>" />
		<form id="form-formazione" name="formazione" action="index.php" method="post">		
			<fieldset id="titolari">
				<h3 class="center">Titolare</h3>
				<h4 class="bold no-margin"><?php echo ucfirst($ruo[$j]); ?></h4><hr />	
				<?php foreach($this->titolari as $key => $val): ?>
						<select disabled="disabled">
							<option></option>
							  <option selected="selected" value="<?php echo $val['idGioc'];?>"><?php  echo $val['cognome']. " ". $val['nome'];  ?></option>
						</select>
						<?php if($j == 0 || $j ==1 ): /*SE È UN DIFENSORE O UN PORTIERE VISULIZZO LA SELECT PER IL CAPITANO */ ?>
						<select disabled="disabled" class="cap">
							<option></option>
							<?php if(array_search($val['idGioc'],$this->cap) != FALSE): ?>
							<option selected="selected"><?php echo array_search($val['idGioc'],$this->cap); ?></option>
							<?php endif; ?>
						</select>
						<?php endif; ?>
						<?php $i++; ?>
						<?php if($i == $this->mod[$j] && $j!=3): $j++;?>
						<h4 class="bold no-margin"><?php echo ucfirst($ruo[$j]); ?></h4><hr />
					<?php $i=0; endif; ?>
				<?php endforeach; ?>
				</fieldset>
				<?php if($this->panchinari != FALSE): ?>
				<fieldset id="panchinari">
					<h3 class="center">Panchina</h3>
					<h4 class="bold no-margin">Giocatori</h4><hr />
					<?php foreach($this->panchinari as $key3=>$val3): ?>
					<select disabled="disabled" >
					<option></option>
							<option selected="selected" value="<?php echo $val3['idGioc']; ?>"><?php  echo $val3['cognome'] . " " . $val3['nome'];?></option>
					</select> 
					<?php endforeach;?>
				</fieldset>
				<?php endif; ?>
			</form>
		<?php endif; ?>
</div>
<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
	<?php if(!$this->formazione && TIMEOUT != '0'): ?>
	<div id="messaggio" class="messaggio neut column last" >
		<img src="<?php echo IMGSURL.'attention-big.png'; ?>" />
		<span>La formazione non è stata impostata</span>
	</div>
	<script type="text/javascript">
		$("#messaggio").click(function () {
			$("div#messaggio").fadeOut("slow");
		});
		</script>
	<?php endif; ?>
	<?php if($_SESSION['logged'] == TRUE): ?>
		<?php require (TPLDIR.'operazioni.tpl.php'); ?>
	<?php endif; ?>
	<?php if(isset($this->modulo) && TIMEOUT != '0'): ?>
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
	<?php if(TIMEOUT != '0'): ?>
	<form class="right last" name="formazione_other" action="<?php echo $this->linksObj->getLink('altreFormazioni'); ?>" method="post">
		<fieldset class="no-margin fieldset">
			<h3 class="no-margin">Guarda le altre formazioni</h3>
			<input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
			<?php if(empty($this->formazioniImpostate)): ?>
				<select name="squadra" disabled="disabled">
					<option>Nessuna form. impostata</option>
			<?php else:?>
				<select name="squadra" onchange="document.formazione_other.submit();">
				<?php foreach($this->formazioniImpostate as $key => $val): ?>
					<option <?php if($this->squadra == $val['idUtente']) echo "selected=\"selected\"" ?> value="<?php echo $val['idUtente']?>"><?php echo $val['nome']?></option>
				<?php endforeach; ?>
			<?php endif; ?>
			</select>
		</fieldset>
		<fieldset class="no-margin fieldset max-large">
				<h3 class="no-margin">Guarda la formazione della giornata</h3>
					<select name="giorn" onchange="document.formazione_other.submit();">
						<?php for($j = GIORNATA ; $j  > 0 ; $j--): ?>
							<option <?php if($this->getGiornata == $j) echo "selected=\"selected\"" ?>><?php echo $j; ?></option>
						<?php endfor; ?>
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
