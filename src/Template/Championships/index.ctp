<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Championship'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Leagues'), ['controller' => 'Leagues', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New League'), ['controller' => 'Leagues', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Seasons'), ['controller' => 'Seasons', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Season'), ['controller' => 'Seasons', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="championships index large-9 medium-8 columns content">
    <h3><?= __('Championships') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('captain') ?></th>
                <th><?= $this->Paginator->sort('number_transferts') ?></th>
                <th><?= $this->Paginator->sort('number_selections') ?></th>
                <th><?= $this->Paginator->sort('minute_lineup') ?></th>
                <th><?= $this->Paginator->sort('points_missed_lineup') ?></th>
                <th><?= $this->Paginator->sort('captain_missed_lineup') ?></th>
                <th><?= $this->Paginator->sort('jolly') ?></th>
                <th><?= $this->Paginator->sort('league_id') ?></th>
                <th><?= $this->Paginator->sort('season_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($championships as $championship): ?>
            <tr>
                <td><?= $this->Number->format($championship->id) ?></td>
                <td><?= h($championship->captain) ?></td>
                <td><?= $this->Number->format($championship->number_transferts) ?></td>
                <td><?= $this->Number->format($championship->number_selections) ?></td>
                <td><?= $this->Number->format($championship->minute_lineup) ?></td>
                <td><?= $this->Number->format($championship->points_missed_lineup) ?></td>
                <td><?= h($championship->captain_missed_lineup) ?></td>
                <td><?= h($championship->jolly) ?></td>
                <td><?= $championship->has('league') ? $this->Html->link($championship->league->name, ['controller' => 'Leagues', 'action' => 'view', $championship->league->id]) : '' ?></td>
                <td><?= $championship->has('season') ? $this->Html->link($championship->season->name, ['controller' => 'Seasons', 'action' => 'view', $championship->season->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $championship->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $championship->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $championship->id], ['confirm' => __('Are you sure you want to delete # {0}?', $championship->id)]) ?>
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
