<div class="row">
    <?php foreach ($this->elencoClub as $club): ?>
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="well clearfix">
                <a class="club" href="<?php echo $this->router->generate('club_show', array('id' => $club->id)); ?>" title="Rosa <?php echo $club->partitivo . " " . $club->nome ?>">
                    <img alt="<?php echo $club ?>" src="<?php echo CLUBSURL . $club->id . '.png' ?>" />
                    <h3><?php echo $club->nome; ?></h3>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
