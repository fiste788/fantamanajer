<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Scores'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Matchdays'), ['controller' => 'Matchdays', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Matchday'), ['controller' => 'Matchdays', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="scores form large-9 medium-8 columns content">
    <?= $this->Form->create($score) ?>
    <fieldset>
        <legend><?= __('Add Score') ?></legend>
        <?php
            echo $this->Form->input('points');
            echo $this->Form->input('real_points');
            echo $this->Form->input('penality_points');
            echo $this->Form->input('penality');
            echo $this->Form->input('team_id', ['options' => $teams]);
            echo $this->Form->input('matchday_id', ['options' => $matchdays]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
