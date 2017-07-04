<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $lineup->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $lineup->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Lineups'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Matchdays'), ['controller' => 'Matchdays', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Matchday'), ['controller' => 'Matchdays', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Dispositions'), ['controller' => 'Dispositions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Disposition'), ['controller' => 'Dispositions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="lineups form large-9 medium-8 columns content">
    <?= $this->Form->create($lineup) ?>
    <fieldset>
        <legend><?= __('Edit Lineup') ?></legend>
        <?php
            echo $this->Form->input('module');
            echo $this->Form->input('jolly');
            echo $this->Form->input('captain_id');
            echo $this->Form->input('vcaptain_id');
            echo $this->Form->input('vvcaptain_id', ['options' => $members, 'empty' => true]);
            echo $this->Form->input('matchday_id', ['options' => $matchdays]);
            echo $this->Form->input('team_id', ['options' => $teams]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
