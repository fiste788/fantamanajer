<form class="form-inline" action="<?php echo $this->router->generate('giocatori_liberi'); ?>" method="post">
    <fieldset>
        <div class="form-group">
            <label for="ruolo">Seleziona il ruolo:</label>
            <select class="form-control" id="ruolo" name="ruolo"">
                <?php foreach ($this->ruoli as $key => $val): ?>
                    <option<?php echo ($this->request->getParam('ruolo') == $key) ? ' selected="selected"' : ''; ?> value="<?php echo $key ?>"><?php echo $val; ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="form-group">
            <label for="sufficenza">Soglia sufficienza:</label>
            <input id="sufficenza" maxlength="3" name="sufficenza" type="text" class="form-control small" value="<?php if ($this->validFilter) echo $this->defaultSufficenza; ?>" />
        </div>
        <div class="form-group">
            <label for="partite">Soglia partite:</label>
            <input id="partite" maxlength="2" name="partite" type="text" class="form-control small" value="<?php if ($this->validFilter) echo $this->defaultPartite; ?>" />
        </div>
        <div class="form-group">
            <input class="btn btn-primary" type="submit" value="OK"/>
        </div>
    </fieldset>
</form>
