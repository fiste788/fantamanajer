<?php if ($this->eventi != FALSE): ?>
    <ul class="unstyled">
        <?php foreach ($this->eventi as $evento): ?>
            <li class="evento well">
                <em><?php echo $evento->data->format("Y-m-d H:i:s"); ?></em>
                <h4>
                    <a href="<?php echo ($evento->tipo != 2 && $_SESSION['logged']) ? $evento->link : '#' ?>">
                        <?php echo $evento->titolo; ?>
                    </a>
                </h4>
                <?php if (isset($evento->content)): ?>
                    <p><?php echo nl2br($evento->content); ?></p>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Nessun evento</p>
<?php endif; ?>
