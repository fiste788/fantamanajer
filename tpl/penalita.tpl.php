<?php $i = 1; ?>
<?php if(isset($this->classificaDett)): ?>
<div id="classifica-container" class="column last">
	<table cellpadding="0" cellspacing="0" class="column last no-margin" style="width:316px;overflow:hidden;">
		<tbody>
			<tr>
				<th style="width:20px">P.</th>
				<th class="nowrap" style="width:180px">Nome</th>
				<th style="width:70px">Punti tot</th>
			</tr>
			<?php foreach($this->classificaDett as $key => $val): ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td class="squadra no-wrap" id="squadra-<?php echo $key; ?>"><?php echo $this->squadre[$key]->nome; ?></td>
				<td><?php echo array_sum($val); ?></td>
			 </tr>
			<?php $i++;$flag = $key; endforeach; ?>
		</tbody>
	</table>
	<div id="tab_classifica" class="column last"  style="height:<?php echo (27 * (count($this->classificaDett) +1)) +18; ?>px">
	<?php $appo = array_keys($this->classificaDett); $i = $appo[0]; ?>
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
				<td<?php echo (isset($this->penalità[$key][$secondKey])) ? ' title="Penalità: ' . $this->penalità[$key][$secondKey] . ' punti" class="rosso"' : ''; ?>>
					<a href="<?php echo $this->linksObj->getLink('dettaglioGiornata',array('giornata'=>$secondKey,'squadra'=>$this->squadre[$key]->idUtente)); ?>"><?php echo $val[$secondKey]; ?></a>
				</td>
				<?php endforeach; ?>
			</tr>
			<?php $i++; endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
	</div>
</div>
<?php endif; ?>
<?php if(isset($this->squadra) && $this->squadra != NULL): ?>
<form class="column last" id="penalità" action="<?php echo $this->linksObj->getLink('penalita'); ?>" method="post">
	<fieldset class="no-margin">
		<input type="hidden" name="lega" value="<?php echo $this->lega; ?>" />
		<input type="hidden" name="squadra" value="<?php echo $this->squadra; ?>" />
		<input type="hidden" name="giornata" value="<?php echo $this->giornata; ?>" />
		<div class="formbox">
			<label for="punti">Punteggio penalità:</label>
			<input type="text" name="punti" id="punti" class="text"<?php echo (isset($this->penalitàSquadra) && $this->penalitàSquadra != FALSE) ? ' value="' . $this->penalitàSquadra->punteggio . '"':''; ?> />
		</div>
		<div class="formbox">
			<label for="motivo">Motivazione penalità:</label>
			<input type="text" name="motivo" id="motivo" class="text"<?php echo (isset($this->penalitàSquadra) && $this->penalitàSquadra != FALSE) ? ' value="' . $this->penalitàSquadra->penalità . '"':''; ?> />
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
<span>Seleziona la squadra</span>
<?php endif; ?>
