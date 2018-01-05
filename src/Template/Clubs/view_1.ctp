<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Club $club
 */
?>
<div class="enllax-container">
    <img data-enllax-ratio=".5" data-enllax-type="foreground" src="http://www.juventusclubmodena.it/jcm/wp-content/uploads/2016/01/Juventus-Wallpaper-2015-2.jpg" />
</div>
<div class="mdl-container">
    <section class="mdl-grid mdl-grid--no-spacing mdl-shadow--4dp">
        <header class="section__play-btn mdl-cell mdl-cell--3-col-desktop mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-color--teal-100 mdl-color-text--white">
            <?= $this->Html->image('clubs/' . $club->id . ".png",['alt'=>$club->name]) ?>
        </header>
        <div class="mdl-card mdl-cell mdl-cell--9-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
            <div class="mdl-card__title">
                <h3 class="mdl-card__title-text"><?php echo $club->name; ?></h3>
            </div>
            <div class="mdl-card__supporting-text">
                Dolore ex deserunt aute fugiat aute nulla ea sunt aliqua nisi cupidatat eu. Nostrud in laboris labore nisi amet do dolor eu fugiat consectetur elit cillum esse.
            </div>
        </div>
    </section>
    <?= $this->element('members',['members' => $club->members, 'showClub' => false]) ?>
</div>