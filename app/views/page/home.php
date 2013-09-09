<?php if ($this->currentGiornata == 1): ?>
    <h2 class="no-margin center">Ricordati di impostare il nome della squadra!</h2>
    <p class="center">Lo trovi nella pagina rosa e lo puoi cambiare entro la seconda giornata di campionato</p>
<?php else: ?>
    <div id="best-player">
        <?php if (!empty($this->bestPlayer)): ?>
            <h2>Migliori giocatori giornata <?php echo $this->giornata; ?></h2>
            <div class="row">
                <?php foreach ($this->bestPlayer as $ruolo => $giocatore): ?>
                    <div id="<?php echo $ruolo ?>" class="col-lg-3 col-md-3 col-sm-6">
                        <div class="well">
                            <h3><?php echo $this->ruoli[$ruolo]->plurale ?></h3>
                            <a class="foto-container" href="<?php echo $this->router->generate('giocatore_show', array('edit' => 'view', 'id' => $giocatore->id)); ?>">
                                <figure>
                                    <?php if (file_exists(PLAYERSDIR . $giocatore->id . '.jpg')): ?>
                                        <img class="foto img-thumbnail" alt="<?php echo $giocatore; ?>" src="<?php echo PLAYERSURL . $giocatore->id . '.jpg'; ?>" />
                                    <?php else: ?>
                                        <img class="foto" alt="Foto sconosciuta" src="<?php echo IMGSURL . 'no-photo.png'; ?>" />
                                    <?php endif; ?>
                                </figure>
                            </a>
                            <h4><a href="<?php echo $this->router->generate('giocatore_show', array('id' => $giocatore->id,'title'=>"-" . $giocatore->__toString())); ?>"><?php echo $giocatore . ": " . $giocatore->punti; ?></a></h4>
                            <ul class="list-unstyled">
                                <?php foreach ($this->bestPlayers[$ruolo] as $key => $giocatore): ?>
                                    <li><a href="<?php echo $this->router->generate('giocatore_show', array('id' => $giocatore->id,'title'=>"-" . $giocatore->__toString())); ?>"><?php echo $giocatore . ": " . $giocatore->punti; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<div class="row">
    <?php if ($this->eventi != FALSE): ?>
        <div id="eventi" class="col-lg-6 col-md-6 col-sm-6">
            <div class="well">
            <h3>Ultimi eventi</h3>
                <div>
                    <ul class="list-unstyled">
                        <?php foreach ($this->eventi as $key => $evento): ?>
                            <li class="eventoHome">
                                <time><?php echo $evento->data->format("Y-m-d H:i:s"); ?></time>&nbsp;
                                <a<?php echo ($evento->tipo != 2) ? ' href="' . $evento->link . '"' : ''; ?> title="<?php echo $evento->content; ?>"><?php echo $evento->titolo; ?></a>
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
    <?php if ($this->articoli != FALSE) : ?>
        <div id="conferenzeStampa" class="col-lg-6 col-md-6 col-sm-6">
            <div class="well">
                <h3>Ultime conferenze stampa</h3>
                <?php foreach ($this->articoli as $key => $articolo): ?>
                    <article class="news">
                        <header>
                            <em>
                                <time><?php echo $articolo->dataCreazione->format("Y-m-d H:i:s"); ?></time>
                                <span class="pull-right">
                                    <?php echo $articolo->username; ?>
                                    <?php if ($_SESSION['logged'] && $_SESSION['idUtente'] == $articolo->idUtente): ?>
                                        <a class="glyphicon glyphicon-edit" href="<?php echo $this->router->generate('articolo_edit', array('id' => $articolo->id)); ?>" title="Modifica">&nbsp;</a>
                                    <?php endif; ?>
                                </span>
                            </em>
                            <h4 class="title"><?php echo $articolo->titolo; ?></h4>
                        </header>
                        <?php if (isset($articolo->sottoTitolo)): ?><div class="abstract"><?php echo $articolo->sottoTitolo; ?></div><?php endif; ?>
                        <div class="text"><?php echo nl2br($articolo->testo); ?></div>
                    </article>
                <?php endforeach; ?>
                <div class="all">
                    <a href="<?php echo $this->router->generate('articoli') ?>">Vedi tutte le conferenze stampa <i class="glyphicon glyphicon-chevron-right"></i></a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>