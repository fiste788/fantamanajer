<?php if(file_exists(WWW_ROOT . 'img' . DS . 'upload' . DS . 'bg' . $team->id . '.jpg')): ?>
    <div data-enllax-ratio="0.5" class="enllax-container" style="background-image:url('../img/upload/bg/<?= $team->id ?>.jpg')"></div>
<?php else: ?>
    <div data-enllax-ratio="0.5" class="enllax-container"></div>
<?php endif; ?>
<div class="title">
    <div class="photo-crop">
        <?php if(file_exists(WWW_ROOT . 'img' . DS . 'upload' . DS . $team->id . '.jpg')): ?>
            <?= $this->Html->image('upload/' . $team->id . '.jpg', ['alt' => $team->name]); ?>
        <?php else: ?>
            <i class="material-icons md-light md-128">flag</i>
        <?php endif; ?>
    </div>
    <h3><?= $team->name; ?></h3>
    <p>
        
    </p>
</div>
<div class="mdl-container">
    <section class="mdl-layout__tab-panel" id="tab_members">
        <?= $this->element('members',['members' => $team->members, 'showClub' => true]) ?>
    </section>
    <?php foreach($tabs as $key => $tab): ?>
        <section class="mdl-layout__tab-panel" id="tab_<?= $key ?>" data-remote="<?= $tab['url'] ?>">
            <div class="mdl-spinner mdl-js-spinner is-active"></div>
        </section>
    <?php endforeach; ?>
</div>