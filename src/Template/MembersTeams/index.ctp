<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MembersTeam[]|\Cake\Collection\CollectionInterface $membersTeams
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Members Team'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="membersTeams index large-9 medium-8 columns content">
    <h3><?= __('Members Teams') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('team_id') ?></th>
                <th><?= $this->Paginator->sort('member_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($membersTeams as $membersTeam): ?>
            <tr>
                <td><?= $this->Number->format($membersTeam->id) ?></td>
                <td><?= $membersTeam->has('team') ? $this->Html->link($membersTeam->team->name, ['controller' => 'Teams', 'action' => 'view', $membersTeam->team->id]) : '' ?></td>
                <td><?= $membersTeam->has('member') ? $this->Html->link($membersTeam->member->id, ['controller' => 'Members', 'action' => 'view', $membersTeam->member->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $membersTeam->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $membersTeam->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $membersTeam->id], ['confirm' => __('Are you sure you want to delete # {0}?', $membersTeam->id)]) ?>
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
