<form class="form-horizontal" method="post" action="<?php echo $this->router->generate('articolo_create'); ?>">
	<fieldset>
		<div class="control-group">
			<label class="control-label" for="title">Titolo: *</label>
			<div class="controls">
				<input class="span6" type="text" maxlength="30" name="articolo[titolo]" id="title" value="<?php echo $this->articolo->titolo; ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="abstract">Sottotitolo:</label>
			<div class="controls">
				<textarea maxlength="75" class="span9" rows="3" cols="80" name="articolo[sottoTitolo]" id="abstract"><?php echo $this->articolo->sottoTitolo; ?></textarea>
				<input class="span1 disabled cont" type="text" disabled="disabled" value="<?php echo 75 - strlen($this->articolo->sottoTitolo); ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="text">Testo: *</label>
			<div class="controls">
				<div id="emoticons">
				<?php foreach($this->emoticons as $key => $val):?>
					<img data-value="<?php echo $val['cod']; ?>" class="emoticon" src="<?php echo EMOTICONSURL . $val['name'] . '.png'; ?>" title="<?php echo $val['title']; ?>" alt="<?php echo $val['cod']; ?>" />
				<?php endforeach; ?>
				</div>
				<textarea maxlength="1000" class="span9" rows="12" cols="80" name="articolo[testo]" id="text"><?php echo $this->articolo->testo; ?></textarea>
				<input class="span1 disabled cont" type="text" disabled="disabled" value="<?php echo 1000 - strlen($this->articolo->testo); ?>" />
			</div>
		</div>
	</fieldset>
	<fieldset>
		<input class="btn btn-primary" type="submit" name="submit" value="OK" />
		<span class="help-block">(*) I campi contrassegnati con l'asterisco sono obbligatori</span>
	</fieldset>
</form>
