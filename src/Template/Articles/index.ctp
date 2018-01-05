<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Article[]|\Cake\Collection\CollectionInterface $articles
 */
?>
<?php if (!empty($articles)): ?>
    <div class="mdl-grid">
        <?php foreach ($articles as $article): ?>
            <div class="mdl-cell mdl-cell--6-col mdl-cell--6-col-tablet md-cell--4-col-phone">
                <?= $this->element('article', ['article' => $article]) ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    Non sono presenti articoli
<?php endif; ?>

