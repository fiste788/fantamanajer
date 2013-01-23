<?php foreach ($this->elencoClub as $club): ?>
    <a class="club" href="<?php echo $this->router->generate('club_show', array('id' => $club->id)); ?>" title="Rosa <?php echo $club->partitivo . " " . $club->nome ?>">
        <img alt="<?php echo $club ?>" src="<?php echo CLUBSURL . $club->id . '.png' ?>" />
    </a>
<?php endforeach; ?>
