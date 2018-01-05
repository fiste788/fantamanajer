<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Transfert $transfert
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Transferts'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Matchdays'), ['controller' => 'Matchdays', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Matchday'), ['controller' => 'Matchdays', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="transferts form large-9 medium-8 columns content">
    <?= $this->Form->create($transfert) ?>
    <fieldset>
        <legend><?= __('Add Transfert') ?></legend>
        <?php
            echo $this->Form->input('old_member_id');
            echo $this->Form->input('new_member_id', ['options' => $members, 'empty' => true]);
            echo $this->Form->input('team_id', ['options' => $teams]);
            echo $this->Form->input('matchday_id', ['options' => $matchdays]);
            echo $this->Form->input('constrained');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
