<form class="form-inline" method="post" action="<?php echo Links::getLink('modificaConferenza',array('id'=>$this->request->get('id'))); ?>">
	<fieldset>
		<div class="formbox">
			<label for="title">Titolo: *</label>
			<input class="span6" type="text" maxlength="30" name="title" id="title" value="<?php echo $this->articolo->title; ?>" />
		</div>
		<div class="formbox">
			<label for="abstract">Sottotitolo:</label>
			<textarea class="span9" rows="3" cols="80" onkeyup="return ismaxlength(this, 75);" name="abstract" id="abstract"><?php echo $this->articolo->abstract; ?></textarea>
			<input class="span1 disabled" id="abstractCont" type="text" disabled="disabled" value="<?php echo 75 - mb_strlen($this->articolo->abstract,'UTF-8'); ?>" />
		</div>
		<div class="formbox">
			<div id="emoticons">
			<?php foreach($this->emoticons as $key => $val):?>
				<img class="emoticon" src="<?php echo EMOTICONSURL . $val['name'] . '.png'; ?>" title="<?php echo $val['title']; ?>" alt="<?php echo $val['cod']; ?>" onclick="addEmoticon('<?php echo addslashes(stripslashes($val['cod'])); ?>');return ismaxlength(document.getElementById('text'), 1000);" />
			<?php endforeach; ?>
			</div>
			<label for="text">Testo: *</label>
			<textarea class="span9" rows="12" cols="80" onkeyup="return ismaxlength(this, 1000);" name="text" id="text"><?php echo $this->articolo->text; ?></textarea>
			<input class="span1 disabled" id="textCont" type="text" disabled="disabled" value="<?php echo 1000 - mb_strlen($this->articolo->text); ?>" />
		</div>
	</fieldset>
	<fieldset>
		<input class="btn btn-primary" type="submit" name="submit" value="OK" />
		<input class="btn" type="submit" name="submit" value="Rimuovi" />
		<p class="help-block">(*) I campi contrassegnati con l'asterisco sono obbligatori</p>
	</fieldset>
</form>
