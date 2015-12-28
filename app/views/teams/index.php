<div class="mdl-grid">
    <?php foreach ($this->teams as $team): ?>
        <div class="mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet md-cell--4-col-phone">
            <div class="mdl-card mdl-shadow--4dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text"><?php echo $team->name; ?></h2>
                </div>
                <div class="mdl-card__media" style="background-image: url(<?= file_exists(UPLOADDIR . 'thumb-small/' . $team->id . '.jpg') ? (UPLOADURL . 'thumb-small/' . $team->id . '.jpg') : (IMGSURL . 'bg-material.png') ?>)">
                    <?php if (file_exists(UPLOADDIR . 'thumb-small/' . $team->id . '.jpg')): ?>

                        <img alt="<?php echo $team->id; ?>" src="<?php echo UPLOADURL . 'thumb-small/' . $team->id . '.jpg'; ?>" />

                    <?php endif; ?>
                </div>
                <div class="mdl-card__supporting-text">
                    The Sky Tower is an observation and telecommunications tower located in Auckland,
                    New Zealand. It is 328 metres (1,076 ft) tall, making it the tallest man-made structure
                    in the Southern Hemisphere.
                </div>
                <div class="mdl-card__actions mdl-card--border">
                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="<?php echo $this->router->generate("teams_show", array('id' => $team->id)); ?>" title="<?php echo $team->name; ?>">Apri</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
