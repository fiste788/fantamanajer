<?php $j =0; $k = 0; ?>
<div id="formazione" class="main-content">
	<?php if(TIMEOUT): ?>
		<h3>Giornata <?php echo GIORNATA; ?></h3>
		<?php if(isset($this->mod) && $this->mod != NULL): ?>
				<div style="width:950px;height:600px;position:relative;border:1px solid #fff;">
					<img style="display:none;" width="930" alt="modulo" id="img-modulo" title="<?php echo substr($this->mod,2) ?>" src="<?php echo IMGSURL.$this->mod.'.png' ?>" />
				
				<form id="form-formazione" name="formazione" action="<?php echo $this->linksObj->getLink('formazione'); ?>" method="post">
					<input type="hidden" name="mod" value="<?php echo $this->mod; ?>">
				<?php foreach($this->giocatori as $key => $val): ?>
					<?php for($i = 0; $i < $this->modulo[$j] ; $i++): ?>
						<select class="titolare <?php echo $this->ruo[$j] ?>" style="position:absolute;<?php echo 'top:'. ((((600-(18*$this->modulo[$j])) / ($this->modulo[$j]+1)) * ($i+1)) + (($i+1) * 18)-24) . 'px;left:' . 240 * $j . 'px'; ?>" name="<?php echo $this->ruo[$j] . '[' . $i . ']' ; ?>">
							<option></option>
							<?php foreach($val as $key3=>$val3): ?>
								<option value="<?php echo $val3['idGioc']; ?>"<?php if(isset($this->titolari[$k]) && $val3['idGioc'] == $this->titolari[$k]) echo ' selected="selected"'; ?>><?php echo $val3['cognome'] . " " . $val3['nome']; ?></option>
						  	<?php  endforeach; ?>
						</select>
					<?php $k++; endfor; ?>
				<?php $j++; endforeach; ?>
				</div>
				<?php foreach($this->elencocap as $key => $val): ?>
				<?php echo $val ?>
				<select class="cap" id="<?php echo $val; ?>" name="cap[<?php echo $val; ?>]">
					<option></option>
					<?php foreach($this->giocatori['P'] as $key2=>$val2): 
					echo $val2['idGioc']."  " . $this->cap[$val]; 
						if($val2['idGioc'] == $this->cap[$val]): ?>
							<option value="<?php echo $val2['idGioc']; ?>" selected="selected"><?php  echo $val2['cognome'] . " " . $val2['nome'];?></option>
						<?php endif; ?>
					<?php endforeach; ?>
					<?php foreach($this->giocatori['D'] as $key2=>$val2): 
						if($val2['idGioc'] == $this->cap[$val]): ?>
							<option value="<?php echo $val2['idGioc']; ?>" selected="selected"><?php  echo $val2['cognome'] . " " . $val2['nome'];?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
				<?php endforeach; ?>
				<fieldset id="panchinari">
					<h3 class="center">Panchina</h3>
					<h4 class="bold no-margin">Giocatori</h4><hr />
					<?php for( $i = 0 ; $i < 7 ; $i++): ?>
					<select class="panch" name="panch[]">
					<option></option>
						<?php for($j = 0 ; $j < count($this->ruo) ; $j++): ?>
							<optgroup label="<?php echo $this->ruo[$j] ?>">
								<?php foreach($this->giocatori[substr($this->ruo[$j],0,1)] as $key3=>$val3): ?>
									<option value="<?php echo $val3['idGioc']; ?>"<?php if(isset($this->panchinari[$i]) && $val3['idGioc'] == $this->panchinari[$i]) echo ' selected="selected"'; ?>><?php  echo $val3['cognome'] . " " . $val3['nome'];?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endfor; ?>
					</select>
					<?php endfor; ?>
					<div class="div-submit">
						<input class="submit dark" type="submit" name="button" value="Invia" />
						<input class="submit dark" type="reset" value="Torna indietro" />
					</div>
					</fieldset>
				</form>
			<?php endif; ?>
	<?php endif; ?>
</div>
<script type="text/javascript">


	$(document).ready(function() {
		$(".P,.D").change(function() {
			$(".cap").empty();
			var array = {C:'<?php echo $this->cap['C'] ?>',VC:'<?php echo $this->cap['VC'] ?>',VVC:'<?php echo $this->cap['VVC'] ?>'};
			$(".P option:selected,.D option:selected").each(function () {
				a = $("#C");
            	if($(this).attr('value') != '')
            		string = '<option value="' + $(this).attr('value') + '" ';
					if($(this).attr('value') == array["C"])
						string = string + 'selected="selected"';
					string = string + '>' + $(this).text() + '</option>';
					a.append(string);
				a = $("#VC");
            	if($(this).attr('value') != '' && $(this).attr('value') != array["VC"])
					string = '<option value="' + $(this).attr('value') + '" ';
					if($(this).attr('value') == array["VC"])
						string = string + 'selected="selected"';
					string = string + '>' + $(this).text() + '</option>';
					a.append(string);
				a = $("#VVC");
            	if($(this).attr('value') != '' && $(this).attr('value') != array["VVC"])
					string = '<option value="' + $(this).attr('value') + '" ';
					if($(this).attr('value') == array["VVC"])
						string = string + 'selected="selected"';
					string = string + '>' + $(this).text() + '</option>';
					a.append(string);
			});
		});
	});
	
	$(document).ready(function() {
		$(".titolare,.panch").change(function() {
			$(".panch option,.titolare option").css('background','#0ff');
			$(".titolare option:selected,.panch option:selected").each(function () {
                $(".panch option[value=" + $(this).attr('value') + "],.titolare option[value=" + $(this).attr('value') + "]").css('background','#fff');
              });
			
		});
	});
	
	<?php if(isset($this->titolari)): ?>
		$(document).ready(function() {
			$(".panch option,.titolare option").css('background','#0ff');
			$(".titolare option:selected,.panch option:selected").each(function () {
                $(".panch option[value=" + $(this).attr('value') + "],.titolare option[value=" + $(this).attr('value') + "]").css('background','#fff');
              });
		});
		
		$(document).ready(function() {
			var array = {C:'<?php echo $this->cap['C'] ?>',VC:'<?php echo $this->cap['VC'] ?>',VVC:'<?php echo $this->cap['VVC'] ?>'};
			$(".P option:selected,.D option:selected").each(function () {
				a = $("#C");
            	if($(this).attr('value') != '' && $(this).attr('value') != array["C"])
					a.append('<option value="' + $(this).attr('value') + '" >' + $(this).text() + '</option>');
				a = $("#VC");
            	if($(this).attr('value') != '' && $(this).attr('value') != array["VC"])
					a.append('<option value="' + $(this).attr('value') + '" >' + $(this).text() + '</option>');
				a = $("#VVC");
            	if($(this).attr('value') != '' && $(this).attr('value') != array["VVC"])
					a.append('<option value="' + $(this).attr('value') + '" >' + $(this).text() + '</option>');
			});
			
		});
	<?php endif; ?>
</script>

<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
		<?php if(!TIMEOUT): //NON PIÙ NECESSARIO QUESTO IF PER L'HEADER LOCATION NELLA CODE' ?>
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
