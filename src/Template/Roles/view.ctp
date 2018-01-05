<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Role'), ['action' => 'edit', $role->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Role'), ['action' => 'delete', $role->id], ['confirm' => __('Are you sure you want to delete # {0}?', $role->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Roles'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Role'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="roles view large-9 medium-8 columns content">
    <h3><?= h($role->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Singolar') ?></th>
            <td><?= h($role->singolar) ?></td>
        </tr>
        <tr>
            <th><?= __('Plural') ?></th>
            <td><?= h($role->plural) ?></td>
        </tr>
        <tr>
            <th><?= __('Abbreviation') ?></th>
            <td><?= h($role->abbreviation) ?></td>
        </tr>
        <tr>
            <th><?= __('Determinant') ?></th>
            <td><?= h($role->determinant) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($role->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Members') ?></h4>
        <?php if (!empty($role->members)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Code Gazzetta') ?></th>
                <th><?= __('Active') ?></th>
                <th><?= __('Player Id') ?></th>
                <th><?= __('Role Id') ?></th>
                <th><?= __('Club Id') ?></th>
                <th><?= __('Season Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($role->members as $members): ?>
            <tr>
                <td><?= h($members->id) ?></td>
                <td><?= h($members->code_gazzetta) ?></td>
                <td><?= h($members->active) ?></td>
                <td><?= h($members->player_id) ?></td>
                <td><?= h($members->role_id) ?></td>
                <td><?= h($members->club_id) ?></td>
                <td><?= h($members->season_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Members', 'action' => 'view', $members->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Members', 'action' => 'edit', $members->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Members', 'action' => 'delete', $members->id], ['confirm' => __('Are you sure you want to delete # {0}?', $members->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
