<form class="form-inline" action="<?php echo $this->router->generate("giocatore_show",array('id'=>'')) ?>" method="get">
    <fieldset>
        <div class="control-group">
            <label for="giocatore">Seleziona il giocatore:</label>
            <select id="giocatore" name="id">
                <?php foreach ($this->elencoGiocatori as $key => $val): ?>
                <option<?php echo ($key == $this->route['params']['id']) ? ' selected="selected"' : ''; ?> value="<?php echo $key; ?>"><?php echo $val->cognome . " " . $val->nome; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </fieldset>
</form>
