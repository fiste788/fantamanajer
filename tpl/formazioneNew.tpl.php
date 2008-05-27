<?php $j =0; $k = 0; ?>
<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'formazione-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Formazione</h2>
</div>
<div id="formazione" class="main-content">
	<?php /* if($this->timeout):*/ ?>
		<h3>Giornata <?php echo $this->giornata; ?></h3>
		<div class="column" style="width:150px; height:300px">
			<div id="droppable-portiere" class="droppable" title="P" style="margin-top:140px; height:20px;"></div>
			<div id="droppable-portiere" class="droppable" title="D" style="height:20px;"></div>
			<div id="droppable-portiere" class="droppable" title="D" style="height:20px;"></div>
			<div id="droppable-portiere" class="droppable" title="D" style="height:20px;"></div>
			<div id="droppable-portiere" class="droppable" title="C" style="height:20px;"></div>
			<div id="droppable-portiere" class="droppable" title="C" style="height:20px;"></div>
			<div id="droppable-portiere" class="droppable" title="C" style="height:20px;"></div>
			<div id="droppable-portiere" class="droppable" title="C" style="height:20px;"></div>
			<div id="droppable-portiere" class="droppable" title="A" style="height:20px;"></div>
			<div id="droppable-portiere" class="droppable" title="A" style="height:20px;"></div>
			<div id="droppable-portiere" class="droppable" title="A" style="height:20px;"></div>
		</div>
			<div id="giocatori" class="column" >
			<?php foreach($this->giocatori as $key=>$val): ?>
				<div id="<?php echo sprintf('%02d', $j); ?>" class="draggable giocatore<?php echo ' '.$val[3]; ?>">
				<?php echo $val[1].' '.$val[2]; ?>
				</div>
			<?php $j++; endforeach; ?>
			</div>
	<?php /*endif;*/ ?>
	<script type="text/javascript">
	$(document).ready(function(){
		$('.droppable').droppable({
			accept: function(draggable) {
				var title = '.'+$(this).attr('title');
				return $(draggable).is(title);
			},
			activeClass: 'droppable-active',
			hoverClass: 'droppable-hover',
			drop: function(ev, ui) {
				if($(this).children("div").hasClass('P')) {
					var id = "#"+$(ui.element).children('div').attr('id').substring(0,2);
					$("#giocatori").children(id).removeClass('hidden');
					$(this).html('<div id="'+ui.draggable.attr('id') +'-embed" class="'+ui.draggable.attr('class')+'">' + $(ui.draggable).text() + '</div>');
					$(this).children("div").draggable({
						helper:"clone",opacity:0.5,revert:true
					});
					$(".draggable").draggable({
						helper:"clone",opacity:0.5,revert:true
					});
				}
				else{
					$(this).html('<div id="'+ui.draggable.attr('id') +'-embed" class="'+ui.draggable.attr('class')+'">' + $(ui.draggable).text() + '</div>');
					$(this).children(".draggable").draggable({
						helper:"clone",opacity:0.5,revert:true
					});
				}
				$(ui.draggable).addClass('hidden');
				$(ui.helper).remove();
			}
		});
		
	});
	$('.draggable').draggable({
		helper:"clone",opacity:0.5,revert:true
	});
	</script>
</div>
<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
		<?php if(!$this->timeout): //NON PIÙ NECESSARIO QUESTO IF PER L'HEADER LOCATION NELLA CODE' ?>
		<div class="messaggio neut column last" >
			<img alt="!" src="<?php echo IMGSURL.'attention-big.png'; ?>" />
			<span>Non puoi effettuare operazioni in questo momento.Aspetta la fine delle partite</span>
		</div>
		<?php endif; ?>
		<?php if($this->err == 1 || $this->err == 3): /*VUOL DIRE CHE C'È UN DOPPIONE O FORMAZIONE NN COMPLETA */ ?>
			<div class="messaggio bad column last">
				<img alt="!" src="<?php echo IMGSURL.'attention-bad-big.png'; ?>" title="Attenzione!" />
				<span><?php if($this->err == 1): ?>Hai inserito dei valori multipli. <?php else: ?>Mancano dei valori. <?php endif; ?>La formazione non è stata modificata</span>
			</div>
		<?php elseif($this->err == 2): /* TUTTO OK */?>
			<div class="messaggio good column last">
					<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
					<span>Operazione effettuata con successo</span>
			</div>
		<?php elseif($this->issetForm != FALSE):  ?>
			<div class="messaggio neut column last">
				<img alt="!" src="<?php echo IMGSURL.'attention-big.png'; ?>" title="Attenzione!" />
				<span>Hai già impostato la formazione. Se la rinvii quella vecchia verrà sovrascritta</span>
			</div>
		<?php endif; ?>
		<?php if($this->issetForm != FALSE || isset($this->err)): ?> 
		<script type="text/javascript">
		$(document).ready(function() {$('.messaggio').show('pulsate',{times: 3 }); });
		$(".messaggio").click(function () {
			$("div.messaggio").fadeOut("slow");
		});
		</script>
		<?php endif; ?>
		<?php if($_SESSION['logged'] == TRUE): ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		<?php endif; ?>
		<form class="column last" name="form_modulo" action="index.php?p=formazione" method="post">
			<fieldset id="modulo" class="no-margin fieldset">
				<h3 class="no-margin">Seleziona il modulo:</h3>
				<select name="mod" onchange="document.form_modulo.submit();">
					<option></option>
					<option value="1-4-4-2" <?php if ($this->value == '1-4-4-2') echo "selected=\"selected\""?>>4-4-2</option>
					<option value="1-3-5-2" <?php if ($this->value == '1-3-5-2') echo "selected=\"selected\""?>>3-5-2</option>
					<option value="1-3-4-3" <?php if ($this->value == '1-3-4-3') echo "selected=\"selected\""?>>3-4-3</option>
					<option value="1-4-5-1" <?php if ($this->value == '1-4-5-1') echo "selected=\"selected\""?>>4-5-1</option>
					<option value="1-4-3-3" <?php if ($this->value == '1-4-3-3') echo "selected=\"selected\""?>>4-3-3</option>
					<option value="1-5-4-1" <?php if ($this->value == '1-5-4-1') echo "selected=\"selected\""?>>5-4-1</option>
					<option value="1-5-3-2" <?php if ($this->value == '1-5-3-2') echo "selected=\"selected\""?>>5-3-2</option>
				</select>
			</fieldset>
		</form>
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
					<?php endforeach;?>
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
