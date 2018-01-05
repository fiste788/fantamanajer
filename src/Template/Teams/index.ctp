<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Team[]|\Cake\Collection\CollectionInterface $teams
 */
?>
<div class="mdl-grid">
    <?php foreach ($teams as $team): ?>
        <div class="mdl-cell mdl-cell--6-col mdl-cell--4-col-desktop mdl-cell--4-col-tablet md-cell--4-col-phone">
            <div class="mdl-card mdl-shadow--4dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text"><?php echo $team->name; ?></h2>
                </div>
                <div class="mdl-card__media" style="background-image: url('../img/bg-md.jpg')">
                    <?php if (file_exists(WWW_ROOT . '/img/upload/thumb-small/' . $team->id . '.jpg')): ?>
                        <img class="img-thumbnail" alt="<?php echo $team->id; ?>" src="<?php echo '/img/upload/thumb-small/' . $team->id . '.jpg'; ?>" />
                    <?php endif; ?>
                    <a class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored card-button-open" href="<?php echo $this->Url->build(['action' => 'view', 'id' => $team->id]); ?>">
                        <i class="material-icons">send</i>
                    </a>
                </div>
                <div class="mdl-card__supporting-text">
                    <div>Proprietario: <?php echo $team->user->username; ?></div>
                    <div>Giornate vinte: <?php echo (isset($team->giornateVinte) && $team->giornateVinte != NULL) ? $team->giornateVinte : 0; ?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>