<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Matchday $matchday
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Matchday'), ['action' => 'edit', $matchday->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Matchday'), ['action' => 'delete', $matchday->id], ['confirm' => __('Are you sure you want to delete # {0}?', $matchday->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Matchdays'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Matchday'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Seasons'), ['controller' => 'Seasons', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Season'), ['controller' => 'Seasons', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Articles'), ['controller' => 'Articles', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Article'), ['controller' => 'Articles', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Lineups'), ['controller' => 'Lineups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Lineup'), ['controller' => 'Lineups', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Ratings'), ['controller' => 'Ratings', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Rating'), ['controller' => 'Ratings', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Scores'), ['controller' => 'Scores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Score'), ['controller' => 'Scores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Transferts'), ['controller' => 'Transferts', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Transfert'), ['controller' => 'Transferts', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="matchdays view large-9 medium-8 columns content">
    <h3><?= h($matchday->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Season') ?></th>
            <td><?= $matchday->has('season') ? $this->Html->link($matchday->season->name, ['controller' => 'Seasons', 'action' => 'view', $matchday->season->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($matchday->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Number') ?></th>
            <td><?= $this->Number->format($matchday->number) ?></td>
        </tr>
        <tr>
            <th><?= __('Date') ?></th>
            <td><?= h($matchday->date) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Articles') ?></h4>
        <?php if (!empty($matchday->articles)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Title') ?></th>
                <th><?= __('Subtitle') ?></th>
                <th><?= __('Body') ?></th>
                <th><?= __('Created At') ?></th>
                <th><?= __('Team Id') ?></th>
                <th><?= __('Matchday Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($matchday->articles as $articles): ?>
            <tr>
                <td><?= h($articles->id) ?></td>
                <td><?= h($articles->title) ?></td>
                <td><?= h($articles->subtitle) ?></td>
                <td><?= h($articles->body) ?></td>
                <td><?= h($articles->created_at) ?></td>
                <td><?= h($articles->team_id) ?></td>
                <td><?= h($articles->matchday_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Articles', 'action' => 'view', $articles->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Articles', 'action' => 'edit', $articles->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Articles', 'action' => 'delete', $articles->id], ['confirm' => __('Are you sure you want to delete # {0}?', $articles->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Lineups') ?></h4>
        <?php if (!empty($matchday->lineups)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Module') ?></th>
                <th><?= __('Jolly') ?></th>
                <th><?= __('Captain Id') ?></th>
                <th><?= __('Vcaptain Id') ?></th>
                <th><?= __('Vvcaptain Id') ?></th>
                <th><?= __('Matchday Id') ?></th>
                <th><?= __('Team Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($matchday->lineups as $lineups): ?>
            <tr>
                <td><?= h($lineups->id) ?></td>
                <td><?= h($lineups->module) ?></td>
                <td><?= h($lineups->jolly) ?></td>
                <td><?= h($lineups->captain_id) ?></td>
                <td><?= h($lineups->vcaptain_id) ?></td>
                <td><?= h($lineups->vvcaptain_id) ?></td>
                <td><?= h($lineups->matchday_id) ?></td>
                <td><?= h($lineups->team_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Lineups', 'action' => 'view', $lineups->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Lineups', 'action' => 'edit', $lineups->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Lineups', 'action' => 'delete', $lineups->id], ['confirm' => __('Are you sure you want to delete # {0}?', $lineups->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Ratings') ?></h4>
        <?php if (!empty($matchday->ratings)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Valued') ?></th>
                <th><?= __('Points') ?></th>
                <th><?= __('Rating') ?></th>
                <th><?= __('Goals') ?></th>
                <th><?= __('Goals Against') ?></th>
                <th><?= __('Goals Victory') ?></th>
                <th><?= __('Goals Tie') ?></th>
                <th><?= __('Assist') ?></th>
                <th><?= __('Yellow Card') ?></th>
                <th><?= __('Red Card') ?></th>
                <th><?= __('Penalities Scored') ?></th>
                <th><?= __('Penalities Taken') ?></th>
                <th><?= __('Present') ?></th>
                <th><?= __('Regular') ?></th>
                <th><?= __('Quotation') ?></th>
                <th><?= __('Member Id') ?></th>
                <th><?= __('Matchday Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($matchday->ratings as $ratings): ?>
            <tr>
                <td><?= h($ratings->id) ?></td>
                <td><?= h($ratings->valued) ?></td>
                <td><?= h($ratings->points) ?></td>
                <td><?= h($ratings->rating) ?></td>
                <td><?= h($ratings->goals) ?></td>
                <td><?= h($ratings->goals_against) ?></td>
                <td><?= h($ratings->goals_victory) ?></td>
                <td><?= h($ratings->goals_tie) ?></td>
                <td><?= h($ratings->assist) ?></td>
                <td><?= h($ratings->yellow_card) ?></td>
                <td><?= h($ratings->red_card) ?></td>
                <td><?= h($ratings->penalities_scored) ?></td>
                <td><?= h($ratings->penalities_taken) ?></td>
                <td><?= h($ratings->present) ?></td>
                <td><?= h($ratings->regular) ?></td>
                <td><?= h($ratings->quotation) ?></td>
                <td><?= h($ratings->member_id) ?></td>
                <td><?= h($ratings->matchday_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Ratings', 'action' => 'view', $ratings->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Ratings', 'action' => 'edit', $ratings->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Ratings', 'action' => 'delete', $ratings->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ratings->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Scores') ?></h4>
        <?php if (!empty($matchday->scores)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Points') ?></th>
                <th><?= __('Real Points') ?></th>
                <th><?= __('Penality Points') ?></th>
                <th><?= __('Penality') ?></th>
                <th><?= __('Team Id') ?></th>
                <th><?= __('Matchday Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($matchday->scores as $scores): ?>
            <tr>
                <td><?= h($scores->id) ?></td>
                <td><?= h($scores->points) ?></td>
                <td><?= h($scores->real_points) ?></td>
                <td><?= h($scores->penality_points) ?></td>
                <td><?= h($scores->penality) ?></td>
                <td><?= h($scores->team_id) ?></td>
                <td><?= h($scores->matchday_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Scores', 'action' => 'view', $scores->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Scores', 'action' => 'edit', $scores->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Scores', 'action' => 'delete', $scores->id], ['confirm' => __('Are you sure you want to delete # {0}?', $scores->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Transferts') ?></h4>
        <?php if (!empty($matchday->transferts)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Old Member Id') ?></th>
                <th><?= __('New Member Id') ?></th>
                <th><?= __('Team Id') ?></th>
                <th><?= __('Matchday Id') ?></th>
                <th><?= __('Constrained') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($matchday->transferts as $transferts): ?>
            <tr>
                <td><?= h($transferts->id) ?></td>
                <td><?= h($transferts->old_member_id) ?></td>
                <td><?= h($transferts->new_member_id) ?></td>
                <td><?= h($transferts->team_id) ?></td>
                <td><?= h($transferts->matchday_id) ?></td>
                <td><?= h($transferts->constrained) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Transferts', 'action' => 'view', $transferts->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Transferts', 'action' => 'edit', $transferts->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Transferts', 'action' => 'delete', $transferts->id], ['confirm' => __('Are you sure you want to delete # {0}?', $transferts->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
