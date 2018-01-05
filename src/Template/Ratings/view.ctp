<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Rating $rating
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Rating'), ['action' => 'edit', $rating->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Rating'), ['action' => 'delete', $rating->id], ['confirm' => __('Are you sure you want to delete # {0}?', $rating->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Ratings'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Rating'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Matchdays'), ['controller' => 'Matchdays', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Matchday'), ['controller' => 'Matchdays', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="ratings view large-9 medium-8 columns content">
    <h3><?= h($rating->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Member') ?></th>
            <td><?= $rating->has('member') ? $this->Html->link($rating->member->id, ['controller' => 'Members', 'action' => 'view', $rating->member->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Matchday') ?></th>
            <td><?= $rating->has('matchday') ? $this->Html->link($rating->matchday->id, ['controller' => 'Matchdays', 'action' => 'view', $rating->matchday->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($rating->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Points') ?></th>
            <td><?= $this->Number->format($rating->points) ?></td>
        </tr>
        <tr>
            <th><?= __('Rating') ?></th>
            <td><?= $this->Number->format($rating->rating) ?></td>
        </tr>
        <tr>
            <th><?= __('Goals') ?></th>
            <td><?= $this->Number->format($rating->goals) ?></td>
        </tr>
        <tr>
            <th><?= __('Goals Against') ?></th>
            <td><?= $this->Number->format($rating->goals_against) ?></td>
        </tr>
        <tr>
            <th><?= __('Goals Victory') ?></th>
            <td><?= $this->Number->format($rating->goals_victory) ?></td>
        </tr>
        <tr>
            <th><?= __('Goals Tie') ?></th>
            <td><?= $this->Number->format($rating->goals_tie) ?></td>
        </tr>
        <tr>
            <th><?= __('Assist') ?></th>
            <td><?= $this->Number->format($rating->assist) ?></td>
        </tr>
        <tr>
            <th><?= __('Penalities Scored') ?></th>
            <td><?= $this->Number->format($rating->penalities_scored) ?></td>
        </tr>
        <tr>
            <th><?= __('Penalities Taken') ?></th>
            <td><?= $this->Number->format($rating->penalities_taken) ?></td>
        </tr>
        <tr>
            <th><?= __('Quotation') ?></th>
            <td><?= $this->Number->format($rating->quotation) ?></td>
        </tr>
        <tr>
            <th><?= __('Valued') ?></th>
            <td><?= $rating->valued ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th><?= __('Yellow Card') ?></th>
            <td><?= $rating->yellow_card ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th><?= __('Red Card') ?></th>
            <td><?= $rating->red_card ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th><?= __('Present') ?></th>
            <td><?= $rating->present ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th><?= __('Regular') ?></th>
            <td><?= $rating->regular ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
