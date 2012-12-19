<form class="form-horizontal" enctype="multipart/form-data" action="<?php echo Links::getLink('utente') ?>" method="post">
    <fieldset>
        <div class="control-group">
            <label class="control-label" for="name">Nome:</label>
            <div class="controls">
                <input id="name" class="text" type="text" maxlength="15" name="utente[nome]" value="<?php echo $this->utente->nome ?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="surname">Cognome:</label>
            <div class="controls">
                <input id="surname" class="text" type="text" maxlength="15" name="utente[cognome]"  value="<?php echo $this->utente->cognome ?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="email">E-mail:</label>
            <div class="controls">
                <input id="email" class="text" type="text" maxlength="30" name="utente[email]"  value="<?php echo $this->utente->email ?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="mailAbilitata">Ricevi email:</label>
            <div class="controls">
                <input type="hidden" name="utente[mailAbilitata]" value="0"/>
                <input id="mailAbilitata" value="1" class="checkbox" type="checkbox" name="utente[mailAbilitata]"<?php echo ($this->utente->isMailAbilitata()) ? ' checked="checked"' : '' ?>/>
            </div>
        </div>
        <?php if (GIORNATA <= 2): ?>
            <div class="control-group">
                <label class="control-label" for="nomeSquadra">Nome squadra:</label>
                <div class="controls">
                    <input id="nomeSquadra" class="text" type="text" maxlength="30" name="utente[nomeSquadra]"  value="<?php echo $this->utente->nomeSquadra ?>"/>
                </div>
            </div>
        <?php endif; ?>
        <div class="control-group">
            <label class="control-label" for="password">Password:</label>
            <div class="controls">
                <input id="password" class="text" type="password" maxlength="12" name="utente[password]"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="passwordrepeat">Ripeti Pass:</label>
            <div class="controls">
                <input id="passwordrepeat" class="text" type="password" maxlength="12" name="utente[passwordrepeat]"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="logo">Carica il tuo logo:</label>
            <div class="controls">
                <input id="logo" class="upload" name="logo" type="file" />
            </div>
        </div>
        <div class="control-group">
            <input type="submit" class="btn btn-primary" name="submit" value="OK" />
        </div>
    </fieldset>
</form>