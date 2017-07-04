<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $league->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $league->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Leagues'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Championships'), ['controller' => 'Championships', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Championship'), ['controller' => 'Championships', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="leagues form large-9 medium-8 columns content">
    <?= $this->Form->create($league) ?>
    <fieldset>
        <legend><?= __('Edit League') ?></legend>
        <?php
            echo $this->Form->input('name');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
