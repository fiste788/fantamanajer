<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Lineup $lineup
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Lineup'), ['action' => 'edit', $lineup->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Lineup'), ['action' => 'delete', $lineup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $lineup->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Lineups'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Lineup'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Matchdays'), ['controller' => 'Matchdays', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Matchday'), ['controller' => 'Matchdays', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Dispositions'), ['controller' => 'Dispositions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Disposition'), ['controller' => 'Dispositions', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="lineups view large-9 medium-8 columns content">
    <h3><?= h($lineup->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Module') ?></th>
            <td><?= h($lineup->module) ?></td>
        </tr>
        <tr>
            <th><?= __('Member') ?></th>
            <td><?= $lineup->has('member') ? $this->Html->link($lineup->member->id, ['controller' => 'Members', 'action' => 'view', $lineup->member->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Matchday') ?></th>
            <td><?= $lineup->has('matchday') ? $this->Html->link($lineup->matchday->id, ['controller' => 'Matchdays', 'action' => 'view', $lineup->matchday->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Team') ?></th>
            <td><?= $lineup->has('team') ? $this->Html->link($lineup->team->name, ['controller' => 'Teams', 'action' => 'view', $lineup->team->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($lineup->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Captain Id') ?></th>
            <td><?= $this->Number->format($lineup->captain_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Vcaptain Id') ?></th>
            <td><?= $this->Number->format($lineup->vcaptain_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Jolly') ?></th>
            <td><?= $lineup->jolly ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Dispositions') ?></h4>
        <?php if (!empty($lineup->dispositions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Position') ?></th>
                <th><?= __('Consideration') ?></th>
                <th><?= __('Lineup Id') ?></th>
                <th><?= __('Member Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($lineup->dispositions as $dispositions): ?>
            <tr>
                <td><?= h($dispositions->id) ?></td>
                <td><?= h($dispositions->position) ?></td>
                <td><?= h($dispositions->consideration) ?></td>
                <td><?= h($dispositions->lineup_id) ?></td>
                <td><?= h($dispositions->member_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Dispositions', 'action' => 'view', $dispositions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Dispositions', 'action' => 'edit', $dispositions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Dispositions', 'action' => 'delete', $dispositions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dispositions->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
