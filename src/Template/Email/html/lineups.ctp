<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Team[] $teams
 */
?>
<?php foreach($teams as $team): ?>
<div>
    <h3><?php echo $team->name; ?></h3>
    <?php if(!empty($team->lineups)): ?>
        <?= $this->element('Email/lineups', [
            'captains' => [
                'C' => $team->lineups[0]->captain_id,
                'VC' => $team->lineups[0]->vcaptain_id,
                'VVC' => $team->lineups[0]->vvcaptain_id
            ],
            'dispositions' => array_slice($team->lineups[0]->dispositions, 0, 11), 
            'caption' => __('Titolari'), 
            'full' => true, 
            'baseUrl' => $baseUrl
            ])
        ?>
        <?= $this->element('Email/lineups', [
            'dispositions' => array_slice($team->lineups[0]->dispositions, 11), 
            'caption' => __('Panchinari'), 
            'full' => true, 
            'baseUrl' => $baseUrl
            ]) 
        ?>
    <?php else: ?>
        <p>Formazione non settata</p>
    <?php endif; ?>
</div>
<?php endforeach; ?>