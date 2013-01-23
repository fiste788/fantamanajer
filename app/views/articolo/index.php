<a href="<?php echo $this->router->generate("articolo_new"); ?>">nuovo</a>
<?php if (!empty($this->articoli)): ?>
    <div class="row-fluid">
        <?php foreach ($this->articoli as $articolo): ?>
            <article class="span6 articolo well">
                <header>
                    <em>
                        <span><?php echo $articolo->dataCreazione->format("Y-m-d H:i:s"); ?></span>
                        <span class="right">
                            <?php echo $articolo->username; ?>
                            <?php if ($_SESSION['logged'] && $_SESSION['idUtente'] == $articolo->idUtente): ?>
                                <a class="icon-edit" href="<?php echo $this->router->generate('articolo_edit', array('id' => $articolo->id,'action' => 'edit')); ?>" title="Modifica">&nbsp;</a>
                            <?php endif; ?>
                        </span>
                    </em>
                    <h3 class="title"><?php echo $articolo->titolo; ?></h3>
                </header>
                <?php if (isset($articolo->sottoTitolo)): ?><div class="abstract"><?php echo $articolo->sottoTitolo; ?></div><?php endif; ?>
                <div class="text"><?php echo nl2br($articolo->testo); ?></div>
            </article>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    Non sono presenti articoli
<?php endif; ?>
