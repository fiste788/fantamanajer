<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Members Team'), ['action' => 'edit', $membersTeam->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Members Team'), ['action' => 'delete', $membersTeam->id], ['confirm' => __('Are you sure you want to delete # {0}?', $membersTeam->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Members Teams'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Members Team'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Teams'), ['controller' => 'Teams', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Team'), ['controller' => 'Teams', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="membersTeams view large-9 medium-8 columns content">
    <h3><?= h($membersTeam->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Team') ?></th>
            <td><?= $membersTeam->has('team') ? $this->Html->link($membersTeam->team->name, ['controller' => 'Teams', 'action' => 'view', $membersTeam->team->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Member') ?></th>
            <td><?= $membersTeam->has('member') ? $this->Html->link($membersTeam->member->id, ['controller' => 'Members', 'action' => 'view', $membersTeam->member->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($membersTeam->id) ?></td>
        </tr>
    </table>
</div>
