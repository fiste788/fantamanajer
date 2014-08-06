<form class="form-inline" action="<?php echo Links::getLink('giocatoriLiberi'); ?>" method="post">
    <fieldset>
        <div class="control-group">
            <input type="hidden" name="p" value="<?php echo $this->request->get('p'); ?>" />
            <label for="ruolo">Seleziona il ruolo:</label>
            <select id="ruolo" name="ruolo" onchange="this.form.submit();">
                <?php foreach ($this->ruoli as $key => $val): ?>
                    <option<?php echo ($this->request->get('ruolo') == $key) ? ' selected="selected"' : ''; ?> value="<?php echo $key ?>"><?php echo $val; ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="control-group">
            <label for="sufficenza">Soglia sufficienza:</label>
            <input id="sufficenza" maxlength="3" name="sufficenza" type="text" class="small" value="<?php if ($this->validFilter) echo $this->defaultSufficenza; ?>" />
        </div>
        <div class="control-group">
            <label for="partite">Soglia partite:</label>
            <input id="partite" maxlength="2" name="partite" type="text" class="small" value="<?php if ($this->validFilter) echo $this->defaultPartite; ?>" />
        </div>
        <div class="control-group">
            <input class="btn btn-primary" type="submit" value="OK"/>
        </div>
    </fieldset>
</form>
