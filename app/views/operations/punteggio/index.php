<form class="form-inline" action="<?php echo $this->router->generate('classifica'); ?>" method="get">
    <fieldset>
        <div class="control-group">
            <label for="giornata">Guarda la classifica alla giornata</label>
            <select class="form-control" id="giornata" name="giornata">
                <?php for ($j = $this->giornate; $j > 0; $j--): ?>
                    <option data-url="<?php echo $this->router->generate('classifica',array('giornata'=>$j)) ?>" <?php echo ($this->getGiornata == $j) ? ' selected="selected"' : ''; ?>><?php echo $j; ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </fieldset>
</form>
