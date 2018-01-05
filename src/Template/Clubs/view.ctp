<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Club $club
 */
?>
<div data-enllax-ratio="0.5" class="enllax-container" style="background-image:url('../img/clubs/bg/<?= $club->id ?>.jpg')"></div>
<div class="title">
    <?= $this->Html->image('clubs/' . $club->id . ".png",['alt'=>$club->name]) ?>
    <h3><?php echo $club->name; ?></h3>
</div>
<div class="mdl-container">
    <?= $this->element('members',['members' => $club->members, 'showClub' => false]) ?>
</div>