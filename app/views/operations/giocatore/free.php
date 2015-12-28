<form class="form-inline" action="<?php echo $this->router->generate('giocatori_liberi'); ?>" method="post">
    <fieldset>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
             <select class="mdl-textfield__select" id="role" name="ruolo"">
                <?php foreach ($this->roles as $key => $role): ?>
                    <option<?php echo ($this->role == $role) ? ' selected="selected"' : ''; ?> value="<?php echo $key ?>"><?php echo $role->singolar; ?></option>
                <?php endforeach ?>
            </select>
            <label for="role" class="mdl-textfield__label">Ruolo:</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input small" id="enough" maxlength="3" name="enough" type="text" value="<?php if ($this->validFilter) echo $this->defaultEnough; ?>" />
            <label class="mdl-textfield__label" for="enough">Soglia sufficienza:</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <label class="mdl-textfield__label" for="match">Soglia partite:</label>
            <input class="mdl-textfield__input small" id="match" maxlength="2" name="match" type="text" value="<?php if ($this->validFilter) echo $this->defaultMatch; ?>" />
        </div>
        <input class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" type="submit" value="OK"/>
    </fieldset>
</form>
