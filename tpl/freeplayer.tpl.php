<?php $ruoli = array('P' => 'Portieri', 'D' => 'Difensori', 'C' => 'Centrocampisti', 'A' =>'Attaccanti') ?>
<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'freeplayer-big.png'; ?>" alt="->" />
	</div>
	<h2 class="column">Giocatori liberi</h2>
</div>
<div id="freeplayer" class="main-content">
	<?php if($this->appo): ?>
	<form name="acq" action="index.php?p=trasferimenti" method="post">
	<table cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<th class="check">Acq.</th>
				<th>&nbsp;</th>
				<th class="cognome">Cognome</th>
				<th class="nome">Nome</th>
				<th class="club">Club</th>
				<th class="club"><a href="<?php 	if(!isset($this->getv) || $this->getorder != 'Voti')
																									echo 'index.php?p=freeplayer&amp;order=Voti&amp;v=desc';
																								elseif($this->getorder == 'Voti')
																								{
																									if($this->getv == 'asc')
																										echo 'index.php?p=freeplayer&amp;order=Voti&amp;v=desc';
																									else
																										echo 'index.php?p=freeplayer&amp;order=Voti&amp;v=asc';
																								}
																								if(isset($this->ruolo))
																									echo '&amp;ruolo=' . $this->ruolo;
																								if(isset($this->suff))
																									echo '&amp;suff=' . $this->suff;
																								if(isset($this->partite))
																									echo '&amp;partite=' . $this->partite;
																										?>
																				">M. voto</a></th>
				<th class="club"><a href="<?php 	if(!isset($this->getv) || $this->getorder != 'PartiteGiocate')
																									echo 'index.php?p=freeplayer&amp;order=PartiteGiocate&amp;v=desc';
																								elseif($this->getorder == 'PartiteGiocate')
																								{
																									if($this->getv == 'asc')
																										echo 'index.php?p=freeplayer&amp;order=PartiteGiocate&amp;v=desc';
																									else
																										echo 'index.php?p=freeplayer&amp;order=PartiteGiocate&amp;v=asc';
																								}
																								if(isset($this->ruolo))
																									echo '&amp;ruolo=' . $this->ruolo;
																								if(isset($this->suff))
																									echo '&amp;suff=' . $this->suff;
																								if(isset($this->partite))
																									echo '&amp;partite=' . $this->partite;
																								?>
																				">Partite</a></th>
			</tr>
			<?php foreach($this->freeplayer as $key => $val): ?>
			<tr>
				<td><input class="radio" type="radio" name="acquista" value="<?php echo $val['IdGioc']; ?>" /></td>
				<td class="tableimg">
				<?php if($val['Voti'] >= $this->suff && $val['PartiteGiocate'] >= $this->partite): ?>
					<img alt="Verde" title="Verde" src="<?php echo IMGSURL.'player-tit.png' ?>"/>
				<?php elseif($val['Voti'] >= $this->suff || $val['PartiteGiocate'] >= $this->partite): ?>
					<img alt="Giallo" title="Giallo" src="<?php echo IMGSURL.'player-panch.png' ?>"/>
				<?php else: ?>
					<img alt="Rosso" title="Rosso" src="<?php echo IMGSURL.'player-rosso.png' ?>"/>
				<?php endif; ?>
				</td>
				<td><?php echo $val['Cognome']; ?></td>
				<td><?php echo $val['Nome']; ?></td>
				<td><?php echo $val['Club']; ?></td>
				<td title="<?php echo $val['VotiAll']; ?>" <?php if($val['Voti'] >= $this->suff) echo "class=\"verde\""; else echo "class=\"rosso\""; ?>><?php echo $val['Voti']; ?></td>
				<td <?php if($val['PartiteGiocate'] >= $this->partite) echo "class=\"verde\""; else echo "class=\"rosso\""; ?>><?php echo $val['PartiteGiocate']; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<p>Se clicchi sul bottone sottostante selezionerai il giocatore per l'acquisto che comunque non avverrà subito e che può essere annullato. Nella pagina che ti apparirà dopo aver cliccato sul bottone ci sono altre informazioni</p>
	<input type="submit" class="submit dark" value="Acquista" />
	</form>
<?php else: ?>
Parametri non validi
<?php endif; ?>
</div>
<div id="squadradett" class="messaggio column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
		<?php if($_SESSION['logged'] == TRUE): ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		<?php endif; ?>
		<form id="freeplayeropt" class="column last" name="ruolo" action="index.php?p=freeplayer" method="get">
			<fieldset class="no-margin fieldset">
				<input type="hidden" name="p" value="<?php echo $_GET['p'];?>" />
				<input type="hidden" name="order" value="<?php echo $this->getorder ;?>" />
				<input type="hidden" name="v" value="<?php echo $this->getv;?>" />
				<h3 class="no-margin">Seleziona il ruolo:</h3>
				<select name="ruolo" onchange="document.ruolo.submit();">
					<option></option>
					<?php foreach($ruoli as $key=>$val): ?>
						<option <?php if($this->ruolo == $key) echo "selected=\"selected\"" ?> value="<?php echo $key?>"><?php echo $val; ?></option>
					<?php endforeach ?>
				</select>
				<div class="field column last">
					<label>Soglia sufficienza:</label>
					<input maxlength="3" name="suff" type="text" class="text" value="<?php echo $this->suff; ?>" />
				</div>
				<div class="field column last">
					<label>Soglia partite:</label>
					<input maxlength="2" name="partite" type="text" class="text" value="<?php echo $this->partite; ?>" />
					</div>
					<input class="submit" type="submit" value="OK"/>
			</fieldset>
		</form>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
