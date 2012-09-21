<form class="form-inline" action="<?php echo Links::getLink('conferenzeStampa'); ?>" method="post">
    <fieldset>
        <div class="control-group">
            <label for="giornata">Seleziona la giornata:</label>
            <select id="giornata" name="giornata" onchange="this.form.submit();">
                <?php if ($this->giornateWithArticoli != FALSE): ?>
                    <?php foreach ($this->giornateWithArticoli as $val): ?>
                        <option<?php echo ($val == $this->giornata) ? ' selected="selected"' : ''; ?>><?php echo $val; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <?php if ($_SESSION['logged']): ?>
            <div class="control-group">
                <a class="btn btn-primary" href="<?php echo Links::getLink('modificaConferenza'); ?>"><i class="icon-plus icon-white"></i>Nuova conferenza stampa</a>
            </div>
        <?php endif; ?>
    </fieldset>
</form>
