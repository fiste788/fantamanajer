<form class="form-inline" action="<?php echo Links::getLink('trasferimenti'); ?>" method="post">
    <fieldset>
        <div class="control-group">
            <input type="hidden" name="p" value="<?php echo $this->request->get('p'); ?>" />
            <label for="id" class="no-margin">Seleziona la squadra:</label>
            <select name="id" onchange="this.form.submit();">
                <?php foreach ($this->elencoSquadre as $val): ?>
                    <option<?php if ($this->filterId == $val->id) echo ' selected="selected"'; ?> value="<?php echo $val->id; ?>"><?php echo $val->nomeSquadra; ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </fieldset>
</form>
