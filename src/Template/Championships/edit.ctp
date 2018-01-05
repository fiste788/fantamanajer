<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Championship $championship
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $championship->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $championship->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Championships'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Leagues'), ['controller' => 'Leagues', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New League'), ['controller' => 'Leagues', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Seasons'), ['controller' => 'Seasons', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Season'), ['controller' => 'Seasons', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="championships form large-9 medium-8 columns content">
    <?= $this->Form->create($championship) ?>
    <fieldset>
        <legend><?= __('Edit Championship') ?></legend>
        <?php
            echo $this->Form->input('captain');
            echo $this->Form->input('number_transferts');
            echo $this->Form->input('number_selections');
            echo $this->Form->input('minute_lineup');
            echo $this->Form->input('points_missed_lineup');
            echo $this->Form->input('captain_missed_lineup');
            echo $this->Form->input('jolly');
            echo $this->Form->input('league_id', ['options' => $leagues]);
            echo $this->Form->input('season_id', ['options' => $seasons]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
