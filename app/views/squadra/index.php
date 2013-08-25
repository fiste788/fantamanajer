<div class="row">
    <?php foreach ($this->elencoSquadre as $squadra): ?>
        <div class="col-lg-3 col-md-3 col-sm-4">
            <div class="well">
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
                    <h3><a href="<?php echo $this->router->generate("squadra_show",array('id' => $squadra->id)); ?>" title="Maggiori informazioni"><?php echo $squadra->nomeSquadra; ?></a></h3>
                    <div class="data">
                        <div>Proprietario: <?php echo $squadra->username; ?></div>
                        <div>Giornate vinte: <?php echo (isset($squadra->giornateVinte) && $squadra->giornateVinte != NULL) ? $squadra->giornateVinte : 0; ?></div>
                    </div>
                    <ul class="list-unstyled">
                        <li>
                            <a href="<?php echo $this->router->generate('trasferimento_index',array('squadra'=>$squadra->id)); ?>" title="Trasferimenti">Trasferimenti</a>
                        </li>
                        <li>
                            <a href="<?php echo "" ?>" title="Formazione">Formazione</a>
                        </li>
                        <?php if ($this->currentGiornata > 1): ?>
                            <li>
                                <a href="<?php echo $this->router->generate('punteggio_show',array('squadra'=>$squadra->id,'giornata'=>$this->ultimaGiornata)) ?>" title="Ultima giornata">Ultima giornata</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>