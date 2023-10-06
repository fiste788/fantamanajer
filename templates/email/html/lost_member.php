<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Score $score
 * @var \App\Model\Entity\Player $player
 */
?>
<p>
    <?= __('{0} has been selected by another team', $player->fullName); ?>
</p>