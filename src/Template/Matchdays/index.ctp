<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Matchday[]|\Cake\Collection\CollectionInterface $matchdays
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Matchday'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Seasons'), ['controller' => 'Seasons', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Season'), ['controller' => 'Seasons', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Articles'), ['controller' => 'Articles', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Article'), ['controller' => 'Articles', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Lineups'), ['controller' => 'Lineups', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Lineup'), ['controller' => 'Lineups', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ratings'), ['controller' => 'Ratings', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Rating'), ['controller' => 'Ratings', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Scores'), ['controller' => 'Scores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Score'), ['controller' => 'Scores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Transferts'), ['controller' => 'Transferts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Transfert'), ['controller' => 'Transferts', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="matchdays index large-9 medium-8 columns content">
    <h3><?= __('Matchdays') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('number') ?></th>
                <th><?= $this->Paginator->sort('date') ?></th>
                <th><?= $this->Paginator->sort('season_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($matchdays as $matchday): ?>
            <tr>
                <td><?= $this->Number->format($matchday->id) ?></td>
                <td><?= $this->Number->format($matchday->number) ?></td>
                <td><?= h($matchday->date) ?></td>
                <td><?= $matchday->has('season') ? $this->Html->link($matchday->season->name, ['controller' => 'Seasons', 'action' => 'view', $matchday->season->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $matchday->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $matchday->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $matchday->id], ['confirm' => __('Are you sure you want to delete # {0}?', $matchday->id)]) ?>
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
