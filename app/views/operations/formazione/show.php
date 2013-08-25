<form class="form-inline" action="<?php echo $this->router->generate('formazione'); ?>" method="get">
    <fieldset>
        <div class="form-group">
            <label for="squadra">Guarda altre formazioni</label>
                <?php if (empty($this->formazioniPresenti)): ?>
                    <select id="squadra" class="form-control" name="utente" disabled="disabled">
                        <option>Nessuna form. impostata</option>
                    </select>
                <?php else: ?>
                    <select id="squadra" class="form-control" name="utente">
                        <?php foreach ($this->formazioniPresenti as $formazione): ?>
                            <option data-url="<?php echo $this->router->generate('formazione_show',array('giornata'=>$this->giornata,'squadra'=>$formazione->idUtente)) ?>" <?php echo ($this->squadra == $formazione->idUtente) ? ' selected="selected"' : ''; ?> value="<?php echo $formazione->idUtente; ?>"><?php echo $this->squadre[$formazione->idUtente]->nomeSquadra; ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="giornata">Guarda formazione alla giornata</label>
            <select class="form-control" id="giornata" name="giornata">
                <?php for ($j = $this->currentGiornata; $j > 0; $j--): ?>
                    <option data-url="<?php echo $this->router->generate('formazione_show',array('giornata'=>$j,'squadra'=>$this->squadra)) ?>" <?php echo ($this->giornata == $j) ? ' selected="selected"' : ''; ?>><?php echo $j; ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </fieldset>
</form>
