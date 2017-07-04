<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Lineup'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Matchdays'), ['controller' => 'Matchdays', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Matchday'), ['controller' => 'Matchdays', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Dispositions'), ['controller' => 'Dispositions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Disposition'), ['controller' => 'Dispositions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="lineups index large-9 medium-8 columns content">
    <h3><?= __('Lineups') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('module') ?></th>
                <th><?= $this->Paginator->sort('jolly') ?></th>
                <th><?= $this->Paginator->sort('captain_id') ?></th>
                <th><?= $this->Paginator->sort('vcaptain_id') ?></th>
                <th><?= $this->Paginator->sort('vvcaptain_id') ?></th>
                <th><?= $this->Paginator->sort('matchday_id') ?></th>
                <th><?= $this->Paginator->sort('team_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lineups as $lineup): ?>
            <tr>
                <td><?= $this->Number->format($lineup->id) ?></td>
                <td><?= h($lineup->module) ?></td>
                <td><?= h($lineup->jolly) ?></td>
                <td><?= $this->Number->format($lineup->captain_id) ?></td>
                <td><?= $this->Number->format($lineup->vcaptain_id) ?></td>
                <td><?= $lineup->has('member') ? $this->Html->link($lineup->member->id, ['controller' => 'Members', 'action' => 'view', $lineup->member->id]) : '' ?></td>
                <td><?= $lineup->has('matchday') ? $this->Html->link($lineup->matchday->id, ['controller' => 'Matchdays', 'action' => 'view', $lineup->matchday->id]) : '' ?></td>
                <td><?= $lineup->has('team') ? $this->Html->link($lineup->team->name, ['controller' => 'Teams', 'action' => 'view', $lineup->team->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $lineup->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $lineup->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $lineup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $lineup->id)]) ?>
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
