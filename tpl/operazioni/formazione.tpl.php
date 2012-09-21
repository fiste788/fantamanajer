<form class="form-inline" action="<?php echo Links::getLink('formazione'); ?>" method="post">
    <fieldset>
        <div class="control-group">
            <input type="hidden" name="p" value="formazione" />
            <label for="squadra">Guarda altre formazioni</label>
            <?php if (empty($this->formazioniPresenti)): ?>
                <select name="utente" disabled="disabled">
                    <option>Nessuna form. impostata</option>
                <?php else: ?>
                    <select name="utente" onchange="this.form.submit();">
                        <?php foreach ($this->formazioniPresenti as $val): ?>
                            <option <?php echo ($this->squadra == $val) ? ' selected="selected"' : ''; ?> value="<?php echo $val; ?>"><?php echo $this->squadre[$val]->nomeSquadra; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
        </div>
        <div class="control-group">
            <label for="giornata">Guarda formazione alla giornata</label>
            <select id="giornata" name="giornata" onchange="this.form.submit();">
                <?php for ($j = GIORNATA; $j > 0; $j--): ?>
                    <option <?php echo ($this->giornata == $j) ? ' selected="selected"' : ''; ?>><?php echo $j; ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </fieldset>
</form>
