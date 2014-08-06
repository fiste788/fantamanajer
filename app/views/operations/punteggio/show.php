<form class="form-inline" action="<?php echo $this->router->generate('punteggio_show'); ?>">
    <fieldset>
        <div class="form-group">
            <label for="giornata">Seleziona la giornata:</label>
            <select class="form-control" id="giornata" name="giornata">
                <?php if ($this->request->getParam('giornata') == null): ?><option></option><?php endif; ?>
                <?php foreach ($this->giornate as $key => $val): ?>
                    <option data-url="<?php echo $this->router->generate('punteggio_show',array('giornata'=>$key,'squadra'=>$this->request->getParam('squadra'))) ?>"<?php echo ($this->request->getParam('giornata') == $val) ? ' selected="selected"' : ''; ?> value="<?php echo $val; ?>"><?php echo $val; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="squadra">Seleziona la squadra:</label>
            <select class="form-control" id="squadra" name="squadra">
                <?php if ($this->request->getParam('squadra') == null): ?><option></option><?php endif; ?>
                <?php foreach ($this->squadre as $key => $val): ?>
                    <option data-url="<?php echo $this->router->generate('punteggio_show',array('giornata'=>$this->request->getParam('giornata'),'squadra'=>$key)) ?>"<?php echo ($this->request->getParam('squadra') == $val->id) ? ' selected="selected"' : ''; ?> value="<?php echo $val->id; ?>"><?php echo $val->nomeSquadra; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </fieldset>
</form>
