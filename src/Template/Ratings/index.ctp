<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Rating'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Matchdays'), ['controller' => 'Matchdays', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Matchday'), ['controller' => 'Matchdays', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="ratings index large-9 medium-8 columns content">
    <h3><?= __('Ratings') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('valued') ?></th>
                <th><?= $this->Paginator->sort('points') ?></th>
                <th><?= $this->Paginator->sort('rating') ?></th>
                <th><?= $this->Paginator->sort('goals') ?></th>
                <th><?= $this->Paginator->sort('goals_against') ?></th>
                <th><?= $this->Paginator->sort('goals_victory') ?></th>
                <th><?= $this->Paginator->sort('goals_tie') ?></th>
                <th><?= $this->Paginator->sort('assist') ?></th>
                <th><?= $this->Paginator->sort('yellow_card') ?></th>
                <th><?= $this->Paginator->sort('red_card') ?></th>
                <th><?= $this->Paginator->sort('penalities_scored') ?></th>
                <th><?= $this->Paginator->sort('penalities_taken') ?></th>
                <th><?= $this->Paginator->sort('present') ?></th>
                <th><?= $this->Paginator->sort('regular') ?></th>
                <th><?= $this->Paginator->sort('quotation') ?></th>
                <th><?= $this->Paginator->sort('member_id') ?></th>
                <th><?= $this->Paginator->sort('matchday_id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ratings as $rating): ?>
            <tr>
                <td><?= $this->Number->format($rating->id) ?></td>
                <td><?= h($rating->valued) ?></td>
                <td><?= $this->Number->format($rating->points) ?></td>
                <td><?= $this->Number->format($rating->rating) ?></td>
                <td><?= $this->Number->format($rating->goals) ?></td>
                <td><?= $this->Number->format($rating->goals_against) ?></td>
                <td><?= $this->Number->format($rating->goals_victory) ?></td>
                <td><?= $this->Number->format($rating->goals_tie) ?></td>
                <td><?= $this->Number->format($rating->assist) ?></td>
                <td><?= h($rating->yellow_card) ?></td>
                <td><?= h($rating->red_card) ?></td>
                <td><?= $this->Number->format($rating->penalities_scored) ?></td>
                <td><?= $this->Number->format($rating->penalities_taken) ?></td>
                <td><?= h($rating->present) ?></td>
                <td><?= h($rating->regular) ?></td>
                <td><?= $this->Number->format($rating->quotation) ?></td>
                <td><?= $rating->has('member') ? $this->Html->link($rating->member->id, ['controller' => 'Members', 'action' => 'view', $rating->member->id]) : '' ?></td>
                <td><?= $rating->has('matchday') ? $this->Html->link($rating->matchday->id, ['controller' => 'Matchdays', 'action' => 'view', $rating->matchday->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $rating->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $rating->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $rating->id], ['confirm' => __('Are you sure you want to delete # {0}?', $rating->id)]) ?>
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
