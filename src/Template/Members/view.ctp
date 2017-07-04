<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Member'), ['action' => 'edit', $member->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Member'), ['action' => 'delete', $member->id], ['confirm' => __('Are you sure you want to delete # {0}?', $member->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Members'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Member'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Players'), ['controller' => 'Players', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Player'), ['controller' => 'Players', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Roles'), ['controller' => 'Roles', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Role'), ['controller' => 'Roles', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Clubs'), ['controller' => 'Clubs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Club'), ['controller' => 'Clubs', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Seasons'), ['controller' => 'Seasons', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Season'), ['controller' => 'Seasons', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Dispositions'), ['controller' => 'Dispositions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Disposition'), ['controller' => 'Dispositions', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Ratings'), ['controller' => 'Ratings', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Rating'), ['controller' => 'Ratings', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="members view large-9 medium-8 columns content">
    <h3><?= h($member->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Player') ?></th>
            <td><?= $member->has('player') ? $this->Html->link($member->player->name, ['controller' => 'Players', 'action' => 'view', $member->player->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Role') ?></th>
            <td><?= $member->has('role') ? $this->Html->link($member->role->id, ['controller' => 'Roles', 'action' => 'view', $member->role->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Club') ?></th>
            <td><?= $member->has('club') ? $this->Html->link($member->club->name, ['controller' => 'Clubs', 'action' => 'view', $member->club->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Season') ?></th>
            <td><?= $member->has('season') ? $this->Html->link($member->season->name, ['controller' => 'Seasons', 'action' => 'view', $member->season->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($member->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Code Gazzetta') ?></th>
            <td><?= $this->Number->format($member->code_gazzetta) ?></td>
        </tr>
        <tr>
            <th><?= __('Active') ?></th>
            <td><?= $this->Number->format($member->active) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Dispositions') ?></h4>
        <?php if (!empty($member->dispositions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Position') ?></th>
                <th><?= __('Consideration') ?></th>
                <th><?= __('Lineup Id') ?></th>
                <th><?= __('Member Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($member->dispositions as $dispositions): ?>
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
    <div class="related">
        <h4><?= __('Related Ratings') ?></h4>
        <?php if (!empty($member->ratings)): ?>
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
            <?php foreach ($member->ratings as $ratings): ?>
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
        <h4><?= __('Related Teams') ?></h4>
        <?php if (!empty($member->teams)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Name') ?></th>
                <th><?= __('User Id') ?></th>
                <th><?= __('Championship Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($member->teams as $teams): ?>
            <tr>
                <td><?= h($teams->id) ?></td>
                <td><?= h($teams->name) ?></td>
                <td><?= h($teams->user_id) ?></td>
                <td><?= h($teams->championship_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Teams', 'action' => 'view', $teams->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Teams', 'action' => 'edit', $teams->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Teams', 'action' => 'delete', $teams->id], ['confirm' => __('Are you sure you want to delete # {0}?', $teams->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
