<?php if ($this->eventi != FALSE): ?>
    <?php foreach ($this->eventi as $evento): ?>
        <div class="evento">
            <em><?php echo $evento->data->format("Y-m-d H:i:s"); ?></em>
            <h4>
                <a href="<?php echo ($evento->tipo != 2 && $_SESSION['logged']) ? $val->link : '#' ?>">
                    <?php echo $evento->titolo; ?>
                </a>
            </h4>
            <?php if (isset($evento->content)): ?>
                <p><?php echo nl2br($evento->content); ?></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Nessun evento</p>
<?php endif; ?>
