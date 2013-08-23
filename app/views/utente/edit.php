<form class="form-horizontal" action="<?php echo $this->router->generate('utente_update') ?>" method="post">
    <fieldset>
        <div class="form-group">
            <label class="control-label col-lg-2 col-md-2 col-sm-2" for="name">Nome:</label>
            <div class="col-lg-10 col-md-10 col-sm-10">
                <input id="name" class="text" type="text" maxlength="15" name="utente[nome]" value="<?php echo $this->utente->nome ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2 col-md-2 col-sm-2" for="surname">Cognome:</label>
            <div class="col-lg-10 col-md-10 col-sm-10">
                <input id="surname" class="text" type="text" maxlength="15" name="utente[cognome]"  value="<?php echo $this->utente->cognome ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2 col-md-2 col-sm-2" for="email">E-mail:</label>
            <div class="col-lg-10 col-md-10 col-sm-10">
                <input id="email" class="text" type="text" maxlength="30" name="utente[email]"  value="<?php echo $this->utente->email ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2 col-md-2 col-sm-2" for="mailAbilitata">Ricevi email:</label>
            <div class="col-lg-10 col-md-10 col-sm-10">
                <input type="hidden" name="utente[mailAbilitata]" value="0"/>
                <input id="mailAbilitata" value="1" class="checkbox" type="checkbox" name="utente[mailAbilitata]"<?php echo ($this->utente->isMailAbilitata()) ? ' checked="checked"' : '' ?>/>
            </div>
        </div>
        <?php if ($this->currentGiornata <= 2): ?>
            <div class="form-group">
                <label class="control-label col-lg-2 col-md-2 col-sm-2" for="nomeSquadra">Nome squadra:</label>
                <div class="col-lg-10 col-md-10 col-sm-10">
                    <input id="nomeSquadra" class="text" type="text" maxlength="30" name="utente[nomeSquadra]"  value="<?php echo $this->utente->nomeSquadra ?>"/>
                </div>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <label class="control-label col-lg-2 col-md-2 col-sm-2" for="password">Password:</label>
            <div class="col-lg-10 col-md-10 col-sm-10">
                <input id="password" class="text" type="password" maxlength="12" name="utente[password]"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2 col-md-2 col-sm-2" for="passwordrepeat">Ripeti Pass:</label>
            <div class="col-lg-10 col-md-10 col-sm-10">
                <input id="passwordrepeat" class="text" type="password" maxlength="12" name="utente[passwordrepeat]"/>
            </div>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="submit" value="OK" />
        </div>
    </fieldset>
</form>