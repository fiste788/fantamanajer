<?php foreach ($teams as $team): ?>
    <div class="col-lg-3 col-md-3 col-sm-4">
        <div class="well center">
            <div>
                <figure>
                    <?php if (file_exists(WWW_ROOT . '/img/upload/thumb-small/' . $team->id . '.jpg')): ?>
                        <a rel="group" href="<?php echo '/img/upload/' . $team->id . '.jpg' ?>" class="fancybox" title="<?php echo $team->name ?>">
                            <img class="img-thumbnail" alt="<?php echo $team->id; ?>" src="<?php echo '/img/upload/thumb-small/' . $team->id . '.jpg'; ?>" />
                        </a>
                    <?php else: ?>
                        <img height="101" class="logo img-thumbnail" alt="<?php echo $team->id; ?>" src="<?php echo 'img/no-foto.png'; ?>" title="<?php echo $team->name; ?>" />
                    <?php endif; ?>
                </figure>
            </div>
            <div>
                <h4><?= $this->Html->link($team->name, ['action' => 'view', $team->id]) ?></h4>
                <div class="data">
                    <div>Proprietario: <?php echo $team->user->username; ?></div>
                    <div>Giornate vinte: <?php echo (isset($squadra->giornateVinte) && $squadra->giornateVinte != NULL) ? $squadra->giornateVinte : 0; ?></div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>