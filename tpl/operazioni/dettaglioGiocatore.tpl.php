<form class="form-inline" action="<?php echo Links::getLink('dettaglioGiocatore'); ?>" method="post">
    <fieldset>
        <div class="control-group">
            <input type="hidden" value="<?php echo $this->request->get('p'); ?>" />
            <input type="hidden" value="<?php echo $this->request->has('edit') ? $this->request->get('edit') : 'view'; ?>" name="edit" />
            <label for="giocatore">Seleziona il giocatore:</label>
            <select name="giocatore" onchange="this.form.submit();">
                <?php foreach ($this->elencoGiocatori as $key => $val): ?>
                    <option<?php echo ($key == $this->request->get('id')) ? ' selected="selected"' : ''; ?> value="<?php echo $key; ?>"><?php echo $val->cognome . " " . $val->nome; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </fieldset>
</form>
