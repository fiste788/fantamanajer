<form enctype="multipart/form-data" action="<?php echo Links::getLink('utente'); ?>" method="post">
	<fieldset>
		<div class="formbox">
			<label for="name">Nome:</label>
			<input id="name" class="text" type="text" maxlength="15" name="nome" value="<?php echo $this->utente->nome; ?>"/>
		</div>
		<div class="formbox">
			<label for="surname">Cognome:</label>
			<input id="surname" class="text" type="text" maxlength="15" name="cognome"  value="<?php echo $this->utente->cognome; ?>"/>
		</div>
		<div class="formbox">
			<label for="email">E-mail:</label>
			<input id="email" class="text" type="text" maxlength="30" name="mail"  value="<?php echo $this->utente->email; ?>"/>
		</div>
		<div class="formbox">
			<label for="abilitaMail">Ricevi email:</label>
			<input id="abilitaMail" class="checkbox" type="checkbox" name="abilitaMail"<?php echo ($this->utente->abilitaMail) ? ' checked="checked"' : ''; ?>/>
		</div>
		<?php if(GIORNATA <= 2): ?>
			<div class="formbox">
				<label for="nomeSquadra">Nome squadra:</label>
				<input id="nomeSquadra" class="text" type="text" maxlength="30" name="nomeSquadra"  value="<?php echo $this->utente->nomeSquadra; ?>"/>
			</div>
		<?php endif; ?>
		<div class="formbox">
			<label for="password">Password:</label>
			<input id="password" class="text" type="password" maxlength="12" name="passwordnew"/>
		</div>
		<div class="formbox">
			<label for="passwordrepeat">Ripeti Pass:</label>
			<input id="passwordrepeat" class="text" type="password" maxlength="12" name="passwordnewrepeat"/>
		</div>
		<div class="formbox">
			<label for="userfile">Carica il tuo logo:</label>
			<input id="userfile" class="upload" name="userfile" type="file" />
		</div>
		<div class="formbox">
			<input type="submit" class="btn primary" name="submit" value="OK" />
		</div>
	</fieldset>
</form>