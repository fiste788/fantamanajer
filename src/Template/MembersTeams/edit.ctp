<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $membersTeam->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $membersTeam->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Members Teams'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="membersTeams form large-9 medium-8 columns content">
    <?= $this->Form->create($membersTeam) ?>
    <fieldset>
        <legend><?= __('Edit Members Team') ?></legend>
        <?php
            echo $this->Form->input('team_id', ['options' => $teams]);
            echo $this->Form->input('member_id', ['options' => $members]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
