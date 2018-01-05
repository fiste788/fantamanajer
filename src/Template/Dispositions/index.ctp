<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Disposition[]|\Cake\Collection\CollectionInterface $dispositions
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Disposition'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Lineups'), ['controller' => 'Lineups', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Lineup'), ['controller' => 'Lineups', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="dispositions index large-9 medium-8 columns content">
    <h3><?= __('Dispositions') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('position') ?></th>
                <th><?= $this->Paginator->sort('consideration') ?></th>
                <th><?= $this->Paginator->sort('lineup_id') ?></th>
                <th><?= $this->Paginator->sort('member_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dispositions as $disposition): ?>
            <tr>
                <td><?= $this->Number->format($disposition->id) ?></td>
                <td><?= $this->Number->format($disposition->position) ?></td>
                <td><?= $this->Number->format($disposition->consideration) ?></td>
                <td><?= $disposition->has('lineup') ? $this->Html->link($disposition->lineup->id, ['controller' => 'Lineups', 'action' => 'view', $disposition->lineup->id]) : '' ?></td>
                <td><?= $disposition->has('member') ? $this->Html->link($disposition->member->id, ['controller' => 'Members', 'action' => 'view', $disposition->member->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $disposition->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $disposition->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $disposition->id], ['confirm' => __('Are you sure you want to delete # {0}?', $disposition->id)]) ?>
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
