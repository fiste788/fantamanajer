<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Disposition'), ['action' => 'edit', $disposition->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Disposition'), ['action' => 'delete', $disposition->id], ['confirm' => __('Are you sure you want to delete # {0}?', $disposition->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Dispositions'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Disposition'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Lineups'), ['controller' => 'Lineups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Lineup'), ['controller' => 'Lineups', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="dispositions view large-9 medium-8 columns content">
    <h3><?= h($disposition->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Lineup') ?></th>
            <td><?= $disposition->has('lineup') ? $this->Html->link($disposition->lineup->id, ['controller' => 'Lineups', 'action' => 'view', $disposition->lineup->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Member') ?></th>
            <td><?= $disposition->has('member') ? $this->Html->link($disposition->member->id, ['controller' => 'Members', 'action' => 'view', $disposition->member->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($disposition->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Position') ?></th>
            <td><?= $this->Number->format($disposition->position) ?></td>
        </tr>
        <tr>
            <th><?= __('Consideration') ?></th>
            <td><?= $this->Number->format($disposition->consideration) ?></td>
        </tr>
    </table>
</div>
