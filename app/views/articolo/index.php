<?php if (!empty($this->articoli)): ?>
    <div class="row">
        <?php foreach ($this->articoli as $articolo): ?>
            <div class="col-lg-6 col-sm-6 articolo">
                <article div class="well">
                    <header>
                        <em>
                            <time><?php echo $articolo->dataCreazione->format("Y-m-d H:i:s"); ?></time>
                            <span class="pull-right">
                                <?php echo $articolo->username; ?>
                                <?php if ($_SESSION['logged'] && $_SESSION['idUtente'] == $articolo->idUtente): ?>
                                    <a href="<?php echo $this->router->generate('articolo_edit', array('id' => $articolo->id,'action' => 'edit')); ?>" title="Modifica"><span class="glyphicon glyphicon-edit"></span></a>
                                <?php endif; ?>
                            </span>
                        </em>
                        <h3 class="text-center"><?php echo $articolo->titolo; ?></h3>
                    </header>
                    <?php if (isset($articolo->sottoTitolo)): ?><div class="abstract"><?php echo $articolo->sottoTitolo; ?></div><?php endif; ?>
                    <div class="text"><?php echo nl2br($articolo->testo); ?></div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    Non sono presenti articoli
<?php endif; ?>
