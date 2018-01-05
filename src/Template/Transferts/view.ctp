<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Transfert $transfert
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Transfert'), ['action' => 'edit', $transfert->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Transfert'), ['action' => 'delete', $transfert->id], ['confirm' => __('Are you sure you want to delete # {0}?', $transfert->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Transferts'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Transfert'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Matchdays'), ['controller' => 'Matchdays', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Matchday'), ['controller' => 'Matchdays', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="transferts view large-9 medium-8 columns content">
    <h3><?= h($transfert->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Member') ?></th>
            <td><?= $transfert->has('member') ? $this->Html->link($transfert->member->id, ['controller' => 'Members', 'action' => 'view', $transfert->member->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Team') ?></th>
            <td><?= $transfert->has('team') ? $this->Html->link($transfert->team->name, ['controller' => 'Teams', 'action' => 'view', $transfert->team->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Matchday') ?></th>
            <td><?= $transfert->has('matchday') ? $this->Html->link($transfert->matchday->id, ['controller' => 'Matchdays', 'action' => 'view', $transfert->matchday->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($transfert->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Old Member Id') ?></th>
            <td><?= $this->Number->format($transfert->old_member_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Constrained') ?></th>
            <td><?= $transfert->constrained ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
