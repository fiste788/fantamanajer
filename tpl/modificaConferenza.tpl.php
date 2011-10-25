<form method="post" action="<?php echo Links::getLink('modificaConferenza',array('id'=>$this->request->get('id'))); ?>">
	<fieldset class="no-margin">
		<div class="formbox">
			<label for="title">Titolo: *</label>
			<input class="text" type="text" maxlength="30" name="title" id="title" value="<?php echo $this->articolo->title; ?>" />
		</div>
		<div class="formbox">
			<label for="abstract">Sottotitolo:</label>
			<textarea rows="3" cols="80" onkeyup="return ismaxlength(this, 75);" name="abstract" id="abstract"><?php echo $this->articolo->abstract; ?></textarea>
			<input class="text disabled" id="abstractCont" type="text" disabled="disabled" value="<?php echo 75 - mb_strlen($this->articolo->abstract,'UTF-8'); ?>" />
		</div>
		<div class="formbox">
			<div id="emoticons">
			<?php foreach($this->emoticons as $key => $val):?>
				<img class="emoticon" src="<?php echo EMOTICONSURL . $val['name'] . '.png'; ?>" title="<?php echo $val['title']; ?>" alt="<?php echo $val['cod']; ?>" onclick="addEmoticon('<?php echo addslashes(stripslashes($val['cod'])); ?>');return ismaxlength(document.getElementById('text'), 1000);" />
			<?php endforeach; ?>
			</div>
			<label for="text">Testo: *</label>
			<textarea rows="12" cols="80" onkeyup="return ismaxlength(this, 1000);" name="text" id="text"><?php echo $this->articolo->text; ?></textarea>
			<input class="text disabled" id="textCont" type="text" disabled="disabled" value="<?php echo 1000 - mb_strlen($this->articolo->text); ?>" />
		</div>
	</fieldset>
	<fieldset class="column">
		<input class="submit" type="submit" name="submit" value="OK" />
		<input class="submit" type="submit" name="submit" value="Rimuovi" />
		<p>(*) I campi contrassegnati con l'asterisco sono obbligatori</p>
	</fieldset>
</form>
