<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Season'), ['action' => 'edit', $season->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Season'), ['action' => 'delete', $season->id], ['confirm' => __('Are you sure you want to delete # {0}?', $season->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Seasons'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Season'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Championships'), ['controller' => 'Championships', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Championship'), ['controller' => 'Championships', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Matchdays'), ['controller' => 'Matchdays', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Matchday'), ['controller' => 'Matchdays', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="seasons view large-9 medium-8 columns content">
    <h3><?= h($season->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($season->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Key Gazzetta') ?></th>
            <td><?= h($season->key_gazzetta) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($season->id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Year') ?></h4>
        <?= $this->Text->autoParagraph(h($season->year)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Championships') ?></h4>
        <?php if (!empty($season->championships)): ?>
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
            <?php foreach ($season->championships as $championships): ?>
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
    <div class="related">
        <h4><?= __('Related Matchdays') ?></h4>
        <?php if (!empty($season->matchdays)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Number') ?></th>
                <th><?= __('Date') ?></th>
                <th><?= __('Season Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($season->matchdays as $matchdays): ?>
            <tr>
                <td><?= h($matchdays->id) ?></td>
                <td><?= h($matchdays->number) ?></td>
                <td><?= h($matchdays->date) ?></td>
                <td><?= h($matchdays->season_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Matchdays', 'action' => 'view', $matchdays->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Matchdays', 'action' => 'edit', $matchdays->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Matchdays', 'action' => 'delete', $matchdays->id], ['confirm' => __('Are you sure you want to delete # {0}?', $matchdays->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Members') ?></h4>
        <?php if (!empty($season->members)): ?>
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
            <?php foreach ($season->members as $members): ?>
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
