<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Event[]|\Cake\Collection\CollectionInterface $events
 */
?>
<ul class="demo-list-two mdl-list">
    <?php foreach ($events as $event): ?>
    <li class="mdl-list__item mdl-list__item--three-line">
        <span class="mdl-list__item-primary-content">
            <span><?= $event->title ?></span>
            <span class="mdl-list__item-text-body">
                <?= $event->body ?>
            </span>
        </span>
        <span class="mdl-list__item-secondary-content">
            <?= h($event->created_at) ?>
        </span>
    </li>
    <?php endforeach; ?>
</ul>