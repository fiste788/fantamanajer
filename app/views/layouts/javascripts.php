<?php if (isset($this->js)): ?>
    <?php foreach ($this->js as $key => $val): ?>
        <?php if (is_array($val)): ?>
            <?php foreach ($val as $val2): ?>
                <script src="<?php echo (substr($key, 0, 11) == 'components/' ? PUBLICURL : JSURL) . $key . '/' . $val2 . '.js'; ?>" type="text/javascript"></script>
            <?php endforeach; ?>
        <?php else: ?>
            <script src="<?php echo (substr($key, 0, 11) == 'components/' ? PUBLICURL : JSURL) . $key . '/' . $val . '.js'; ?>" type="text/javascript"></script>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (file_exists(JAVASCRIPTSDIR . 'pages/' . $this->page . '.js')): ?>
    <script src="<?php echo JSURL . 'pages/' . $this->page . '.js'; ?>" type="text/javascript"></script>
<?php endif; ?>