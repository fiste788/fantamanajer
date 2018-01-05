<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Club[]|\Cake\Collection\CollectionInterface $clubs
 */
?>
<div class="mdl-grid">
    <?php foreach ($clubs as $club): ?>
        <div class="mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet md-cell--4-col-phone">
            <div class="mdl-card mdl-shadow--4dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text"><?php echo $club->name; ?></h2>
                </div>
                <div class="mdl-card__media">
                    <?= $this->Html->image('clubs/' . $club->id . '.png',['alt'=>$club->name]) ?>
                </div>
                <div class="mdl-card__supporting-text">
                    The Sky Tower is an observation and telecommunications tower located in Auckland,
                    New Zealand. It is 328 metres (1,076 ft) tall, making it the tallest man-made structure
                    in the Southern Hemisphere.
                </div>
                <div class="mdl-card__actions mdl-card--border">
                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="<?php echo $this->Url->build(['controller' => 'Clubs', 'action' => 'view', $club->id]); ?> ?>" title="Rosa <?php echo $club->partitive . " " . $club->name ?>">Apri</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
