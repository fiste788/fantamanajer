<form class="form-inline" action="<?php echo $this->router->generate("club_show",array('id'=>'')) ?>" method="get">
    <fieldset>
        <div class="form-group">
            <label for="club">Seleziona il club:</label>
            <select class="form-control" name="id">
                <?php if ($this->elencoClub != FALSE): ?>
                    <?php foreach ($this->elencoClub as $key => $val): ?>
                        <option<?php echo ($key == $this->route['params']['id']) ? ' selected="selected"' : ''; ?> value="<?php echo $key; ?>"><?php echo $val->nome ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </fieldset>
</form>
