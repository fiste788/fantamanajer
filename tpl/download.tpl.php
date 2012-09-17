<form class="form-horizontal" action="<?php echo Links::getLink('download'); ?>" method="post">
    <fieldset>
        <div class="control-group">
            <label class="control-label">Formato</label>
            <div class="controls">
                <label class="radio">
                    <input class="radio" type="radio" name="type" value="csv" />CSV
                </label>
                <label class="radio">
                    <input class="radio" type="radio" name="type" value="xml" />XML
                </label>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Giornata</label>
            <div class="controls">
                <select disabled="disabled" id="giornataSelect" name="giornata">
                    <option></option>
                    <option value="all">Tutte le giornate</option>
                    <?php if (isset($this->filesVoti)): ?>
                        <?php foreach ($this->filesVoti as $val): ?>
                            <option value="<?php echo $val; ?>"><?php echo $val; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <input class="btn btn-primary" type="submit" name="submit" value="Download"/>
    </fieldset>
</form>
