<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Selection[]|\Cake\Collection\CollectionInterface $selections
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Selection'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="selections index large-9 medium-8 columns content">
    <h3><?= __('Selections') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('number_selections') ?></th>
                <th><?= $this->Paginator->sort('team_id') ?></th>
                <th><?= $this->Paginator->sort('old_member_id') ?></th>
                <th><?= $this->Paginator->sort('new_member_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($selections as $selection): ?>
            <tr>
                <td><?= $this->Number->format($selection->id) ?></td>
                <td><?= $this->Number->format($selection->number_selections) ?></td>
                <td><?= $selection->has('team') ? $this->Html->link($selection->team->name, ['controller' => 'Teams', 'action' => 'view', $selection->team->id]) : '' ?></td>
                <td><?= $this->Number->format($selection->old_member_id) ?></td>
                <td><?= $selection->has('member') ? $this->Html->link($selection->member->id, ['controller' => 'Members', 'action' => 'view', $selection->member->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $selection->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $selection->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $selection->id], ['confirm' => __('Are you sure you want to delete # {0}?', $selection->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
