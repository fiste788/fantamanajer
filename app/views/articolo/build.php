<form class="form-horizontal" role="form" method="post" action="<?php echo $this->router->generate('articolo_create'); ?>">
	<fieldset>
		<div class="form-group">
			<label class="control-label" for="title">Titolo: *</label>
			<input class="form-control" type="text" maxlength="30" name="articolo[titolo]" id="title" value="<?php echo $this->articolo->titolo; ?>" />
		</div>
		<div class="form-group">
			<label class="control-label" for="abstract">Sottotitolo:</label>
			<div class="row">
                <div class="col-lg-11 col-md-11 col-sm-10 col-xs-10">
                    <textarea maxlength="75" class="form-control" rows="3" cols="80" name="articolo[sottoTitolo]" id="abstract"><?php echo $this->articolo->sottoTitolo; ?></textarea>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2">
                    <input class="form-control disabled cont" type="text" disabled="disabled" value="<?php echo 75 - strlen($this->articolo->sottoTitolo); ?>" />
                </div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label" for="text">Testo: *</label>
			<div class="row">
				<div id="emoticons">
				<?php foreach($this->emoticons as $key => $val):?>
					<img data-value="<?php echo $val['cod']; ?>" class="emoticon" src="<?php echo EMOTICONSURL . $val['name'] . '.png'; ?>" title="<?php echo $val['title']; ?>" alt="<?php echo $val['cod']; ?>" />
				<?php endforeach; ?>
				</div>
                <div class="col-lg-11 col-md-11 col-sm-10 col-xs-10">
                    <textarea maxlength="1000" class="form-control" rows="12" cols="80" name="articolo[testo]" id="text"><?php echo $this->articolo->testo; ?></textarea>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2">
                    <input class="form-control disabled cont" type="text" disabled="disabled" value="<?php echo 1000 - strlen($this->articolo->testo); ?>" />
                </div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<input class="btn btn-primary" type="submit" name="submit" value="OK" />
		<span class="help-block">(*) I campi contrassegnati con l'asterisco sono obbligatori</span>
	</fieldset>
</form>
