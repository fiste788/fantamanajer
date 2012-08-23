<form class="form-horizontal" enctype="multipart/form-data" action="<?php echo Links::getLink('utente'); ?>" method="post">
	<fieldset>
		<div class="control-group">
			<label class="control-label" for="name">Nome:</label>
			<div class="controls">
				<input id="name" class="text" type="text" maxlength="15" name="nome" value="<?php echo $this->utente->nome; ?>"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="surname">Cognome:</label>
			<div class="controls">
				<input id="surname" class="text" type="text" maxlength="15" name="cognome"  value="<?php echo $this->utente->cognome; ?>"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="email">E-mail:</label>
			<div class="controls">
				<input id="email" class="text" type="text" maxlength="30" name="mail"  value="<?php echo $this->utente->email; ?>"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="abilitaMail">Ricevi email:</label>
			<div class="controls">
				<input id="abilitaMail" class="checkbox" type="checkbox" name="abilitaMail"<?php echo ($this->utente->abilitaMail) ? ' checked="checked"' : ''; ?>/>
			</div>
		</div>
		<?php if(GIORNATA <= 2): ?>
			<div class="control-group">
				<label class="control-label" for="nomeSquadra">Nome squadra:</label>
				<div class="controls">
					<input id="nomeSquadra" class="text" type="text" maxlength="30" name="nomeSquadra"  value="<?php echo $this->utente->nomeSquadra; ?>"/>
				</div>
			</div>
		<?php endif; ?>
		<div class="control-group">
			<label class="control-label" for="password">Password:</label>
			<div class="controls">
				<input id="password" class="text" type="password" maxlength="12" name="passwordnew"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="passwordrepeat">Ripeti Pass:</label>
			<div class="controls">
				<input id="passwordrepeat" class="text" type="password" maxlength="12" name="passwordnewrepeat"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="userfile">Carica il tuo logo:</label>
			<div class="controls">
				<input id="userfile" class="upload" name="userfile" type="file" />
			</div>
		</div>
		<div class="control-group">
			<input type="submit" class="btn btn-primary" name="submit" value="OK" />
		</div>
	</fieldset>
</form>