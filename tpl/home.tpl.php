<?php if (GIORNATA == 1): ?>
    <h2 class="no-margin center">Ricordati di impostare il nome della squadra!</h2>
    <p class="center">Lo trovi nella pagina rosa e lo puoi cambiare entro la seconda giornata di campionato</p>
<?php else: ?>
    <div id="best-player">
        <?php if (!empty($this->bestPlayer)): ?>
            <h3>Migliori giocatori giornata <?php echo $this->giornata; ?></h3>
            <div class="row-fluid">
                <?php foreach ($this->bestPlayer as $ruolo => $giocatore): ?>
                    <div id="<?php echo $ruolo ?>" class="span3 well">
                        <h4><?php echo $this->ruoli[$ruolo]->plurale ?></h4>
                        <a class="foto-container" href="<?php echo Links::getLink('dettaglioGiocatore', array('edit' => 'view', 'id' => $giocatore->id)); ?>">
                            <figure>
                                <?php if (file_exists(PLAYERSDIR . $giocatore->id . '.jpg')): ?>
                                    <img class="foto img-polaroid" alt="<?php echo $giocatore; ?>" src="<?php echo PLAYERSURL . $giocatore->id . '.jpg'; ?>" />
                                <?php else: ?>
                                    <img class="foto" alt="Foto sconosciuta" src="<?php echo IMGSURL . 'no-photo.png'; ?>" />
                                <?php endif; ?>
                            </figure>
                        </a>
                        <h4><a href="<?php echo Links::getLink('dettaglioGiocatore', array('edit' => 'view', 'id' => $giocatore->id)); ?>"><?php echo $giocatore . ": " . $giocatore->punti; ?></a></h4>
                        <ul class="no-dotted">
                            <?php foreach ($this->bestPlayers[$ruolo] as $key => $giocatore): ?>
                                <li><a href="<?php echo Links::getLink('dettaglioGiocatore', array('edit' => 'view', 'id' => $giocatore->id)); ?>"><?php echo $giocatore . ": " . $giocatore->punti; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<div class="row-fluid">
    <?php if ($this->eventi != FALSE): ?>
        <div id="eventi" class="span6 well">
            <h3>Ultimi eventi</h3>
            <div>
                <ul>
                    <?php foreach ($this->eventi as $key => $evento): ?>
                        <li class="eventoHome">
                            <em><?php echo $evento->data->format("Y-m-d H:i:s"); ?></em>&nbsp;
                            <a<?php echo ($evento->tipo != 2) ? ' href="' . $evento->link . '"' : ''; ?> title="<?php echo $evento->content; ?>"><?php echo $evento->titolo; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="all">
                    <a href="<?php echo Links::getLink('feed'); ?>">Vedi tutti gli eventi <i class="icon-chevron-right"></i></a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($this->articoli != FALSE) : ?>
        <div id="conferenzeStampa" class="span6 well">
            <h3>Ultime conferenze stampa</h3>
            <?php foreach ($this->articoli as $key => $articolo): ?>
                <article id="news">
                    <header>
                        <em>
                            <span><?php FirePHP::getInstance()->log($articolo); echo $articolo->dataCreazione->format("Y-m-d H:i:s"); ?></span>
                            <span class="right">
                                <?php echo $articolo->username; ?>
                                <?php if ($_SESSION['logged'] && $_SESSION['idUtente'] == $articolo->idUtente): ?>
                                    <a class="icon-edit" href="<?php echo Links::getLink('modificaConferenza', array('id' => $articolo->id)); ?>" title="Modifica">&nbsp;</a>
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
                <a href="<?php echo Links::getLink('conferenzeStampa'); ?>">Vedi tutte le conferenze stampa <i class="icon-chevron-right"></i></a>
            </div>
        </div>
    <?php endif; ?>
</div>