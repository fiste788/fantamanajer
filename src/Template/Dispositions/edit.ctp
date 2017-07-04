<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $disposition->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $disposition->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Dispositions'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Lineups'), ['controller' => 'Lineups', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Lineup'), ['controller' => 'Lineups', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="dispositions form large-9 medium-8 columns content">
    <?= $this->Form->create($disposition) ?>
    <fieldset>
        <legend><?= __('Edit Disposition') ?></legend>
        <?php
            echo $this->Form->input('position');
            echo $this->Form->input('consideration');
            echo $this->Form->input('lineup_id', ['options' => $lineups]);
            echo $this->Form->input('member_id', ['options' => $members]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
