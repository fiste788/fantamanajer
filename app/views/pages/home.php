<?php if ($this->currentMatchday->number < 3): ?>
    <h2 class="center">Ricordati di impostare il nome della squadra!</h2>
    <p class="center">Lo trovi nella pagina rosa e lo puoi cambiare entro la seconda giornata di campionato</p>
<?php else: ?>
    <div id="best-player">
        <?php if (!empty($this->bestPlayer)): ?>
            <h2>Migliori giocatori giornata <?php echo $this->matchday; ?></h2>
            <div class="mdl-grid">
                <?php foreach ($this->bestPlayer as $role => $member): ?>
                    <div id="<?php echo $role ?>" class="mdl-cell mdl-cell--3-col mdl-cell--3-col-tablet md-cell--4-col-phone">
                        <div class="mdl-card mdl-shadow--4dp">
                            <header class="mdl-card__title">
                                <h2 class="mdl-card__title-text"><?php echo $member->player . ": " . $member->points; ?></h2>
                            </header>
                            <div class="mdl-card__media">
                                <figure>
                                    <?php if (file_exists(PLAYERSDIR . $member->id . '.jpg')): ?>
                                        <img class="foto img-thumbnail" alt="<?php echo $member->player; ?>" src="<?php echo PLAYERSURL . $member->id . '.jpg'; ?>" />
                                    <?php else: ?>
                                        <img class="foto" alt="Foto sconosciuta" src="<?php echo IMGSURL . 'no-photo.png'; ?>" />
                                    <?php endif; ?>
                                </figure>
                            </div>

                            <div class="mdl-card__supporting-text">
                                <ul class="mdl-list">
                                    <?php foreach ($this->bestPlayers[$role] as $key => $member): ?>
                                        <li class="mdl-list__item">
                                            <a class="mdl-navigation__link" href="<?php echo $this->router->generate('members_show', array('id' => $member->id)); ?>">
                                                <span class="mdl-list__item-primary-content"><?php echo $member->player . ": " . $member->points; ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<div class="row">
    <?php if (!empty($this->events)): ?>
        <div id="eventi" class="col-lg-6 col-md-6 col-sm-6">
            <div class="well">
                <h3>Ultimi eventi</h3>
                <div>
                    <ul class="list-unstyled">
                        <?php foreach ($this->events as $key => $event): ?>
                            <li class="eventoHome">
                                <time><?php echo $event->created_at->format("Y-m-d H:i:s"); ?></time>&nbsp;
                                <a<?php echo ($event->type != 2) ? ' href="' . $event->link . '"' : ''; ?> title="<?php echo $event->content; ?>"><?php echo $event->title; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="all">
                        <a href="<?php echo $this->router->generate('feed'); ?>">Vedi tutti gli eventi <i class="glyphicon glyphicon-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($this->articles != FALSE) : ?>
        <div id="conferenzeStampa" class="col-lg-6 col-md-6 col-sm-6">
            <div class="well">
                <h3>Ultime conferenze stampa</h3>
                <?php foreach ($this->articles as $key => $article): ?>
                    <div class="mdl-cell mdl-cell--6-col mdl-cell--6-col-tablet md-cell--4-col-phone">
                        <article class="mdl-card mdl-shadow--4dp">
                            <header class="mdl-card__title">
                                <h2 class="mdl-card__title-text"><?php echo $article->title; ?></h2>
                                <div class="mdl-card__subtitle-text">
                                    <em>
                                        <time><?php echo $article->created_at->format("Y-m-d H:i:s"); ?></time>
                                        <span class="pull-right">
                                            <?php echo $article->team; ?>
                                        </span>
                                    </em>
                                    <?php echo $article->subtitle; ?>
                                </div>
                            </header>
                            <div class="mdl-card__supporting-text">
                                <?php echo nl2br($article->body); ?>
                            </div>
                            <div class="mdl-card__menu">
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" id="menu-<?= $article->id ?>">
                                    <i class="material-icons">more_vert</i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="menu-<?= $article->id ?>">
                                    <?php if ($_SESSION['logged'] && $_SESSION['user_id'] == $article->team->id): ?>
                                        <li class="mdl-menu__item"><a class="mdl-navigation__link" href="<?php echo $this->router->generate('articles_edit', array('id' => $article->id, 'action' => 'edit')); ?>"><i class="material-icons">edit</i>Modifica</a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
                <div class="all">
                    <a href="<?php echo $this->router->generate('articles') ?>">Vedi tutte le conferenze stampa <i class="glyphicon glyphicon-chevron-right"></i></a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>