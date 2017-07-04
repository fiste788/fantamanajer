<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Ratings'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Members'), ['controller' => 'Members', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Member'), ['controller' => 'Members', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Matchdays'), ['controller' => 'Matchdays', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Matchday'), ['controller' => 'Matchdays', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="ratings form large-9 medium-8 columns content">
    <?= $this->Form->create($rating) ?>
    <fieldset>
        <legend><?= __('Add Rating') ?></legend>
        <?php
            echo $this->Form->input('valued');
            echo $this->Form->input('points');
            echo $this->Form->input('rating');
            echo $this->Form->input('goals');
            echo $this->Form->input('goals_against');
            echo $this->Form->input('goals_victory');
            echo $this->Form->input('goals_tie');
            echo $this->Form->input('assist');
            echo $this->Form->input('yellow_card');
            echo $this->Form->input('red_card');
            echo $this->Form->input('penalities_scored');
            echo $this->Form->input('penalities_taken');
            echo $this->Form->input('present');
            echo $this->Form->input('regular');
            echo $this->Form->input('quotation');
            echo $this->Form->input('member_id', ['options' => $members]);
            echo $this->Form->input('matchday_id', ['options' => $matchdays]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
