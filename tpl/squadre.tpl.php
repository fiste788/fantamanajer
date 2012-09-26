<div class="row-fluid">
    <?php $i = 0;
    foreach ($this->elencoSquadre as $squadra): $i++;
        ?>
        <div class="span3 well">
            <div>
                <figure>
                    <?php if (file_exists(UPLOADDIR . 'thumb-small/' . $squadra->id . '.jpg')): ?>
                        <a rel="group" href="<?php echo UPLOADURL . $squadra->id . '.jpg' ?>" class="fancybox" title="<?php echo $squadra->nomeSquadra ?>">
                            <img alt="<?php echo $squadra->id; ?>" src="<?php echo UPLOADURL . 'thumb-small/' . $squadra->id . '.jpg'; ?>" />
                        </a>
                    <?php else: ?>
                        <img height="93" width="124" class="logo" alt="<?php echo $squadra->id; ?>" src="<?php echo IMGSURL . 'no-foto.png'; ?>" title="<?php echo $squadra->nomeSquadra; ?>" />
                    <?php endif; ?>
                </figure>
            </div>
            <div>
                <h3><a href="<?php echo Links::getLink('dettaglioSquadra', array('squadra' => $squadra->id)); ?>" title="Maggiori informazioni"><?php echo $squadra->nomeSquadra; ?></a></h3>
                <div class="data">
                    <div>Proprietario: <?php echo $squadra->username; ?></div>
                    <div>Giornate vinte: <?php echo (isset($squadra->giornateVinte) && $squadra->giornateVinte != NULL) ? $squadra->giornateVinte : 0; ?></div>
                </div>
                <ul class="no-dotted">
                    <li>
                        <a href="<?php echo Links::getLink('trasferimenti', array('id' => $squadra->id)); ?>" title="Trasferimenti">Trasferimenti</a>
                    </li>
                    <li>
                        <a href="<?php echo Links::getLink('formazione', array('utente' => $squadra->id, 'giornata' => GIORNATA)); ?>" title="Formazione">Formazione</a>
                    </li>
                    <?php if (GIORNATA > 1): ?>
                        <li>
                            <a href="<?php echo Links::getLink('dettaglioGiornata', array('giornata' => $this->ultimaGiornata, 'squadra' => $squadra->id)); ?>" title="Ultima giornata">Ultima giornata</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <?php if ($i % 4 == 0): ?></div><div class="row-fluid"><?php endif; ?>
<?php endforeach; ?>
</div>