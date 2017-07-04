<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $selection->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $selection->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Selections'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="selections form large-9 medium-8 columns content">
    <?= $this->Form->create($selection) ?>
    <fieldset>
        <legend><?= __('Edit Selection') ?></legend>
        <?php
            echo $this->Form->input('number_selections');
            echo $this->Form->input('team_id', ['options' => $teams]);
            echo $this->Form->input('old_member_id');
            echo $this->Form->input('new_member_id', ['options' => $members, 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
