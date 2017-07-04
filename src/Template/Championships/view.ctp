<?php foreach($tabs as $key => $tab): ?>
    <section class="mdl-layout__tab-panel" id="tab_<?= $key ?>" data-remote="<?= $tab['url'] ?>">
        <div class="mdl-spinner mdl-js-spinner is-active"></div>
    </section>
<?php endforeach; ?>
