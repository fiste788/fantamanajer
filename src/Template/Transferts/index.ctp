<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Transfert[]|\Cake\Collection\CollectionInterface $transferts
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Transfert'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Matchdays'), ['controller' => 'Matchdays', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Matchday'), ['controller' => 'Matchdays', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="transferts index large-9 medium-8 columns content">
    <h3><?= __('Transferts') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('old_member_id') ?></th>
                <th><?= $this->Paginator->sort('new_member_id') ?></th>
                <th><?= $this->Paginator->sort('team_id') ?></th>
                <th><?= $this->Paginator->sort('matchday_id') ?></th>
                <th><?= $this->Paginator->sort('constrained') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transferts as $transfert): ?>
            <tr>
                <td><?= $this->Number->format($transfert->id) ?></td>
                <td><?= $this->Number->format($transfert->old_member_id) ?></td>
                <td><?= $transfert->has('member') ? $this->Html->link($transfert->member->id, ['controller' => 'Members', 'action' => 'view', $transfert->member->id]) : '' ?></td>
                <td><?= $transfert->has('team') ? $this->Html->link($transfert->team->name, ['controller' => 'Teams', 'action' => 'view', $transfert->team->id]) : '' ?></td>
                <td><?= $transfert->has('matchday') ? $this->Html->link($transfert->matchday->id, ['controller' => 'Matchdays', 'action' => 'view', $transfert->matchday->id]) : '' ?></td>
                <td><?= h($transfert->constrained) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $transfert->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $transfert->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $transfert->id], ['confirm' => __('Are you sure you want to delete # {0}?', $transfert->id)]) ?>
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
