<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Article $article
 */
?>
<div class="mdl-container">
    <section class="mdl-grid mdl-grid--no-spacing mdl-shadow--4dp">
        <?= $this->Form->create($article) ?>
            <fieldset>
            <?php
                echo $this->Form->input('title',['label'=> __('Titolo *')]);
                echo $this->Form->input('subtitle', ['label' => __('Sotto titolo')]);
                echo $this->Form->input('body', ['label' => __('Testo *')]);
            ?>
            </fieldset>
            <?= $this->Form->button(__('Ok')) ?>
            <span class="help-block">(*) I campi contrassegnati con l'asterisco sono obbligatori</span>
        <?= $this->Form->end() ?>
    </section>
</div>