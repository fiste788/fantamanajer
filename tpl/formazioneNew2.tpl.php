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
		<div id="campo" class="column">
			<div id="portiere" class="droppable" name="P"></div>
			<div id="difensori" class="droppable" name="D"></div>
			<div id="centrocampisti" class="droppable" name="C"></div>
			<div id="attaccanti" class="droppable" name="A"></div>
		</div>
			<div id="giocatori" class="column" >
			<?php foreach($this->giocatori as $key=>$val): ?>
				<div id="<?php echo sprintf('%02d', $j); ?>" name="<?php echo $val[3]; ?>" class="draggable giocatore<?php echo ' '.$val[3]; ?>">
				<?php echo $val[1].' '.substr($val[2],0,1).'.'; ?>
				</div>
			<?php $j++; endforeach; ?>
			</div>
	<?php /*endif;*/ ?>
	<script type="text/javascript">
	function prova(){
		$(".draggable").draggable({
			helper:"clone",opacity:0.5,revert:true
		});
		$('.droppable').droppable({
			accept: function(draggable) {
				var data = new Array();
				data['P']='1';
				data['D']='5';
				data['C']='5';
				data['A']='3';
				var n = 0;
				var nTot = 0;
				$(this).find("div").each(function () {
					n++;
				});
				$(this).parent().find("div.embed").each(function () {
					nTot++;
				});
				var numMax = data[($(this).attr('name'))];
				var nome = $(this).attr('name');
				if(n < numMax && nTot < 11 && $(draggable).attr('name') == nome || $(draggable).is('.embed'))
					return true;
			},
			activeClass: 'droppable-active',
			hoverClass: 'droppable-hover',
			greedy: true,
			drop: function(ev,ui) {
					$(this).append('<div id="'+ui.draggable.attr('id') +'-embed" name="'+ ui.draggable.attr('name') +'" style="'+ ui.helper.attr('style') +'" class="embed droppable '+ui.draggable.attr('class')+'">' + $(ui.draggable).text() + '</div>');
					$(this).children('div').css('opacity','1');
					if((ui.draggable).parent().attr('id') == 'giocatori')
						$(ui.draggable).addClass('hidden');
					else
						$(ui.draggable).remove();
					$(ui.helper).remove();
					$(".draggable").draggable({
						helper:"clone",opacity:0.5,revert:true
					});
				}
			});
			$(".draggable").bind('click',function() {
				prova();
			});
		};
		$(".draggable").bind('click',function() {
				prova();
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
