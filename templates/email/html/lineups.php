<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Team[]|\Cake\Collection\CollectionInterface $teams
 * @var mixed $baseUrl
 */
?>
<?php foreach ($teams as $team) : ?>
    <div>
        <h3><?php echo $team->name; ?></h3>
        <?php if (!empty($team->lineups)) : ?>
            <?= $this->element('email/lineups', [
                'captains' => [
                    'C' => $team->lineups[0]->captain_id,
                    'VC' => $team->lineups[0]->vcaptain_id,
                    'VVC' => $team->lineups[0]->vvcaptain_id
                ],
                'dispositions' => array_slice($team->lineups[0]->dispositions, 0, 11),
                'caption' => __('Regular'),
                'full' => true,
                'baseUrl' => $baseUrl
            ])
            ?>
            <?= $this->element('email/lineups', [
                'dispositions' => array_slice($team->lineups[0]->dispositions, 11),
                'caption' => __('Not regular'),
                'full' => true,
                'baseUrl' => $baseUrl
            ])
            ?>
        <?php else : ?>
            <p><?= __('Missing lineup') ?></p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
