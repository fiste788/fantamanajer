<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Matchday $matchday
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Matchdays'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Seasons'), ['controller' => 'Seasons', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Season'), ['controller' => 'Seasons', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Articles'), ['controller' => 'Articles', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Article'), ['controller' => 'Articles', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Lineups'), ['controller' => 'Lineups', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Lineup'), ['controller' => 'Lineups', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ratings'), ['controller' => 'Ratings', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Rating'), ['controller' => 'Ratings', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Scores'), ['controller' => 'Scores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Score'), ['controller' => 'Scores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Transferts'), ['controller' => 'Transferts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Transfert'), ['controller' => 'Transferts', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="matchdays form large-9 medium-8 columns content">
    <?= $this->Form->create($matchday) ?>
    <fieldset>
        <legend><?= __('Add Matchday') ?></legend>
        <?php
            echo $this->Form->input('number');
            echo $this->Form->input('date');
            echo $this->Form->input('season_id', ['options' => $seasons]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
