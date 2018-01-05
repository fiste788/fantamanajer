<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\League[]|\Cake\Collection\CollectionInterface $leagues
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New League'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Championships'), ['controller' => 'Championships', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Championship'), ['controller' => 'Championships', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="leagues index large-9 medium-8 columns content">
    <h3><?= __('Leagues') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($leagues as $league): ?>
            <tr>
                <td><?= $this->Number->format($league->id) ?></td>
                <td><?= h($league->name) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $league->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $league->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $league->id], ['confirm' => __('Are you sure you want to delete # {0}?', $league->id)]) ?>
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
