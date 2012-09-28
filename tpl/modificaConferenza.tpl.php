<form class="form-horizontal" method="post" action="<?php echo Links::getLink('modificaConferenza',array('id'=>$this->request->get('id'))); ?>">
	<fieldset>
		<div class="control-group">
			<label class="control-label" for="title">Titolo: *</label>
			<div class="controls">
				<input class="span6" type="text" maxlength="30" name="titolo" id="title" value="<?php echo $this->articolo->titolo; ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="abstract">Sottotitolo:</label>
			<div class="controls">
				<textarea class="span9" rows="3" cols="80" onkeyup="return ismaxlength(this, 75);" name="sottoTitolo" id="abstract"><?php echo $this->articolo->sottoTitolo; ?></textarea>
				<input class="span1 disabled" id="sottoTitoloCont" type="text" disabled="disabled" value="<?php echo 75 - mb_strlen($this->articolo->sottoTitolo,'UTF-8'); ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="text">Testo: *</label>
			<div class="controls">
				<div id="emoticons">
				<?php foreach($this->emoticons as $key => $val):?>
					<img class="emoticon" src="<?php echo EMOTICONSURL . $val['name'] . '.png'; ?>" title="<?php echo $val['title']; ?>" alt="<?php echo $val['cod']; ?>" onclick="addEmoticon('<?php echo addslashes(stripslashes($val['cod'])); ?>');return ismaxlength(document.getElementById('text'), 1000);" />
				<?php endforeach; ?>
				</div>
				<textarea class="span9" rows="12" cols="80" onkeyup="return ismaxlength(this, 1000);" name="testo" id="text"><?php echo $this->articolo->testo; ?></textarea>
				<input class="span1 disabled" id="testoCont" type="text" disabled="disabled" value="<?php echo 1000 - mb_strlen($this->articolo->testo); ?>" />
			</div>
		</div>
	</fieldset>
	<fieldset>
		<input class="btn btn-primary" type="submit" name="submit" value="OK" />
		<input class="btn" type="submit" name="submit" value="Rimuovi" />
		<span class="help-block">(*) I campi contrassegnati con l'asterisco sono obbligatori</span>
	</fieldset>
</form>
