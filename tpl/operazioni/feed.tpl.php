<form class="form-inline" action="<?php echo Links::getLink('feed'); ?>" method="post">
    <fieldset>
        <div class="control-group">
            <input type="hidden" name="p" value="<?php echo $this->request->get('p'); ?>" />
            <label for="evento">Seleziona il tipo di evento:</label>
            <select name="evento" onchange="this.form.submit();">
                <option value="0">Tutti gli eventi</option>
                <option<?php echo ($this->request->get('evento') == '1') ? ' selected="selected"' : ''; ?> value="1">Conferenze stampa</option>
                <option<?php echo ($this->request->get('evento') == '2') ? ' selected="selected"' : ''; ?> value="2">Giocatore selezionato</option>
                <option<?php echo ($this->request->get('evento') == '3') ? ' selected="selected"' : ''; ?> value="3">Formazione impostata</option>
                <option<?php echo ($this->request->get('evento') == '4') ? ' selected="selected"' : ''; ?> value="4">Trasferimento</option>
                <option<?php echo ($this->request->get('evento') == '5') ? ' selected="selected"' : ''; ?> value="5">Ingresso nuovo giocatore</option>
                <option<?php echo ($this->request->get('evento') == '6') ? ' selected="selected"' : ''; ?> value="6">Uscita giocatore</option>
            </select>
        </div>
    </fieldset>
</form>
