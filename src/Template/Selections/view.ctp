<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Selection $selection
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Selection'), ['action' => 'edit', $selection->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Selection'), ['action' => 'delete', $selection->id], ['confirm' => __('Are you sure you want to delete # {0}?', $selection->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Selections'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Selection'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="selections view large-9 medium-8 columns content">
    <h3><?= h($selection->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Team') ?></th>
            <td><?= $selection->has('team') ? $this->Html->link($selection->team->name, ['controller' => 'Teams', 'action' => 'view', $selection->team->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Member') ?></th>
            <td><?= $selection->has('member') ? $this->Html->link($selection->member->id, ['controller' => 'Members', 'action' => 'view', $selection->member->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($selection->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Number Selections') ?></th>
            <td><?= $this->Number->format($selection->number_selections) ?></td>
        </tr>
        <tr>
            <th><?= __('Old Member Id') ?></th>
            <td><?= $this->Number->format($selection->old_member_id) ?></td>
        </tr>
    </table>
</div>
