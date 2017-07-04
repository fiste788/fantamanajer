<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit League'), ['action' => 'edit', $league->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete League'), ['action' => 'delete', $league->id], ['confirm' => __('Are you sure you want to delete # {0}?', $league->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Leagues'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New League'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Championships'), ['controller' => 'Championships', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Championship'), ['controller' => 'Championships', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="leagues view large-9 medium-8 columns content">
    <h3><?= h($league->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($league->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($league->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Championships') ?></h4>
        <?php if (!empty($league->championships)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Captain') ?></th>
                <th><?= __('Number Transferts') ?></th>
                <th><?= __('Number Selections') ?></th>
                <th><?= __('Minute Lineup') ?></th>
                <th><?= __('Points Missed Lineup') ?></th>
                <th><?= __('Captain Missed Lineup') ?></th>
                <th><?= __('Jolly') ?></th>
                <th><?= __('League Id') ?></th>
                <th><?= __('Season Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($league->championships as $championships): ?>
            <tr>
                <td><?= h($championships->id) ?></td>
                <td><?= h($championships->captain) ?></td>
                <td><?= h($championships->number_transferts) ?></td>
                <td><?= h($championships->number_selections) ?></td>
                <td><?= h($championships->minute_lineup) ?></td>
                <td><?= h($championships->points_missed_lineup) ?></td>
                <td><?= h($championships->captain_missed_lineup) ?></td>
                <td><?= h($championships->jolly) ?></td>
                <td><?= h($championships->league_id) ?></td>
                <td><?= h($championships->season_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Championships', 'action' => 'view', $championships->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Championships', 'action' => 'edit', $championships->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Championships', 'action' => 'delete', $championships->id], ['confirm' => __('Are you sure you want to delete # {0}?', $championships->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
