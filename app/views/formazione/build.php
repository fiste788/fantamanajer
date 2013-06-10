<?php $ruolo = ""; ?>
<?php if (!$this->stagioneFinita || $this->giornata != $this->currentGiornata): ?>
    <div id="giocatori"<?php if ($this->squadra != $_SESSION['idUtente']) echo ' class="hidden"'; ?>>
        <?php foreach ($this->giocatori as $val): ?>
            <?php if ($val->ruolo != $ruolo && $ruolo != "") echo '</div>'; ?>
            <?php if ($ruolo != $val->ruolo) echo '<div class="ruoli ' . $val->ruolo . '">'; ?>
            <div id="<?php echo $val->id; ?>"  data-ruolo="<?php echo $val->ruolo; ?>" class="draggable giocatore <?php echo $val->ruolo; ?>">
                <?php if (file_exists(PLAYERSDIR . $val->id . '.jpg')): ?>
                    <img alt="<?php echo $val->id; ?>" src="<?php echo PLAYERSURL . $val->id; ?>.jpg" />
                <?php endif; ?>
                <p><?php echo $val->cognome . ' ' . $val->nome; ?></p>
            </div>
            <?php $ruolo = ($ruolo != $val->ruolo) ? $val->ruolo : $ruolo; ?>
        <?php endforeach; ?>
    </div>
    </div>
    <h3>Giornata <?php echo $this->giornata; ?></h3>
    <div id="stadio">
        <div id="campo" data-edit="<?php echo($_SESSION['idUtente'] == $this->squadra) ? "true" : "false" ?>" data-modulo="<?php echo htmlspecialchars(json_encode($this->modulo)); ?>">
            <div id="P" class="droppable"></div>
            <div id="D" class="droppable"></div>
            <div id="C" class="droppable"></div>
            <div id="A" class="droppable"></div>
        </div>
        <div id="right">
            <div id="capitani">
                <h3>Capitani</h3>
                <div id="cap-C" class="droppable"></div>
                <div id="cap-VC" class="droppable"></div>
                <div id="cap-VVC" class="droppable"></div>
            </div>
            <form class="form-inline" action="<?php echo $this->router->generate('formazione'); ?>" method="post">
                <fieldset id="titolari-field">
                    <?php for ($i = 0; $i < 11; $i++): ?>
                        <input<?php if (isset($this->formazione->giocatori[$i]) && !empty($this->formazione->giocatori[$i]->idGiocatore)) echo ' value="' . $this->formazione->giocatori[$i]->idGiocatore . '"'; ?> id="gioc-<?php echo $i; ?>" type="hidden" name="titolari[<?php echo $i; ?>]" />
                    <?php endfor; ?>
                </fieldset>
                <fieldset id="panchina-field">
                    <?php for ($i = 0; $i < 7; $i++): ?>
                        <input<?php if (isset($this->panchinari[$i]) && !empty($this->panchinari[$i])) echo ' value="' . $this->panchinari[$i] . '"'; ?> id="panchField-<?php echo $i; ?>" type="hidden" name="panchinari[<?php echo $i; ?>]" />
                    <?php endfor; ?>
                </fieldset>
                <fieldset id="capitani-field">
                    <input value="<?php if (isset($this->formazione)) echo $this->formazione->idCapitano; ?>" id="C" type="hidden" name="C" />
                    <input value="<?php if (isset($this->formazione)) echo $this->formazione->idVCapitano; ?>" id="VC" type="hidden" name="VC" />
                    <input value="<?php if (isset($this->formazione)) echo $this->formazione->idVVCapitano; ?>" id="VVC" type="hidden" name="VVC" />
                </fieldset>
                <fieldset>
                    <?php if ($_SESSION['datiLega']->jolly && (!$this->usedJolly || $this->formazione->getJolly())): ?>
                        <label class="checkbox" for="jolly">
                            <input type="checkbox" value="1" name="jolly" id="jolly" <?php if (isset($this->formazione) && $this->formazione->getJolly()) echo ' checked="checked"'; ?> />Jolly
                        </label>
                    <?php endif; ?>
                    <?php if ($this->giornata == $this->currentGiornata): ?>
                        <input name="submit" type="submit" class="btn btn-primary" value="Invia" />
                    <?php endif; ?>
                </fieldset>
            </form>
            <div id="panchina">
                <h3>Panchinari</h3>
                <?php for ($i = 0; $i < 7; $i++): ?>
                    <div id="panch-<?php echo $i; ?>" class="droppable"></div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>La stagione è finita. Non puoi settare la formazione ora</p>
<?php endif; ?>
