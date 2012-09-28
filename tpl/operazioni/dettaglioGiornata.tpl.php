<form class="form-inline" action="<?php echo Links::getLink('dettaglioGiornata'); ?>" method="post">
    <fieldset>
        <div class="control-group">
            <input type="hidden" name="p" value="<?php echo $this->request->get('p'); ?>" />
            <label for="giornata">Seleziona la giornata:</label>
            <select name="giornata" onchange="this.form.submit();">
                <?php if (!$this->request->has('giornata')): ?><option></option><?php endif; ?>
                <?php foreach ($this->giornate as $key => $val): ?>
                    <option<?php echo ($this->request->get('giornata') == $val) ? ' selected="selected"' : ''; ?> value="<?php echo $val; ?>"><?php echo $val; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="control-group">
            <label for="squadra">Seleziona la squadra:</label>
            <select name="squadra" onchange="this.form.submit();">
                <?php if (!$this->request->has('squadra')): ?><option></option><?php endif; ?>
                <?php foreach ($this->squadre as $key => $val): ?>
                    <option<?php echo ($this->request->get('squadra') == $val->id) ? ' selected="selected"' : ''; ?> value="<?php echo $val->id; ?>"><?php echo $val->nomeSquadra; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </fieldset>
</form>
