<form class="form-inline" action="<?php echo $this->router->generate('articoli'); ?>" method="get">
    <fieldset>
        <div class="form-group">
            <label for="giornata">Seleziona la giornata:</label>
            <select class="form-control" id="giornata" name="giornata">
                <?php if ($this->giornateWithArticoli != FALSE): ?>
                    <?php foreach ($this->giornateWithArticoli as $val): ?>
                        <option data-url="<?php echo $this->router->generate('articoli',array('giornata'=>$val)) ?>" <?php echo ($val == $this->giornata) ? ' selected="selected"' : ''; ?>><?php echo $val; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <?php if ($_SESSION['logged']): ?>
            <div class="form-group">
                <a class="btn btn-primary" href="<?php echo $this->router->generate('articolo_new'); ?>"><span class="glyphicon glyphicon-plus"></span> Nuova conferenza stampa</a>
            </div>
        <?php endif; ?>
    </fieldset>
</form>
