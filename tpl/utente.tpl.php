<form enctype="multipart/form-data" id="userdata" action="<?php echo Links::getLink('utente'); ?>" method="post">
	<fieldset class="column no-margin no-padding">
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
		<h4 class="no-margin">Carica il tuo logo:</h4>
		<input class="upload" name="userfile" type="file" /><br />
		<input type="submit" class="submit" name="submit" value="OK" />
	</fieldset>
</form>