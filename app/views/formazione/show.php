<?php if (!$this->stagioneFinita || $this->giornata != $this->currentGiornata): ?>
<form action="<?php echo $this->router->generate('formazione'); ?>" method="post">
    <div id="advanced" class="hidden-sm hidden-xs">
        <?php if(!empty($this->giocatori)): ?>
            <div id="giocatori" class="fade hidden clearfix"<?php if ($this->squadra != $_SESSION['idUtente']) echo 'data-hidden="true"'; ?>>
                <?php foreach ($this->giocatori as $ruolo => $giocatori): ?>
                    <div class="ruoli <?php echo $ruolo ?>">
                        <?php foreach ($giocatori as $giocatore): ?>
                            <div id="<?php echo $giocatore->id; ?>"  data-ruolo="<?php echo $giocatore->ruolo; ?>" data-cognome="<?php echo $giocatore->cognome; ?>" class="draggable giocatore <?php echo $giocatore->ruolo; ?>">
                                <?php if (file_exists(PLAYERSDIR . $giocatore->id . '.jpg')): ?>
                                    <img class="img-responsive" alt="<?php echo $giocatore->id; ?>" src="<?php echo PLAYERSURL . $giocatore->id; ?>.jpg" />
                                <?php endif; ?>
                                <p><?php echo $giocatore->cognome . ' ' . $giocatore->nome; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <h3>Giornata <?php echo $this->giornata; ?></h3>
        <div id="stadio" class="clearfix">
            <div id="campo" data-edit="<?php echo($_SESSION['idUtente'] == $this->squadra && $this->currentGiornata == $this->giornata) ? "true" : "false" ?>" data-modulo="<?php echo htmlspecialchars(json_encode($this->modulo)); ?>">
                <div id="P" class="droppable"></div>
                <div id="D" class="droppable"></div>
                <div id="C" class="droppable"></div>
                <div id="A" class="droppable"></div>
            </div>
            <div id="right">
                <div id="capitani">
                    <h3>Capitani</h3>
                    <div id="idCapitano" class="droppable" data-symbol="C"></div>
                    <div id="idVCapitano" class="droppable" data-symbol="VC"></div>
                    <div id="idVVCapitano" class="droppable" data-symbol="VVC"></div>
                </div>
                <?php if ($_SESSION['datiLega']->jolly && (!$this->usedJolly || $this->formazione->getJolly())): ?>
                    <div class="checkbox-inline">
                        <label for="jolly">
                            <input type="checkbox" value="1" name="formazione[jolly]" id="jolly" <?php if (!is_null($this->formazione) && $this->formazione->getJolly()) echo ' checked="checked"'; ?> /> Jolly
                        </label>
                    </div>
                <?php endif; ?>
                <?php if ($this->giornata == $this->currentGiornata && $this->squadra == $_SESSION['idUtente']): ?>
                    <input name="submit" type="submit" class="btn btn-primary" value="Invia" />
                <?php endif; ?>
                <div id="panchina">
                    <h3>Panchinari</h3>
                    <?php for ($i = 0; $i < 7; $i++): ?>
                        <div id="panch-<?php echo $i; ?>" class="droppable" data-symbol="<?php echo $i ?>"></div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="visible-xs visible-sm">
        <fieldset>
            <label>Modulo:</label>
            <select class="form-control" name="modulo">
                <?php if(!isset($this->modulo)): ?><option></option><?php endif; ?>
                <?php foreach($this->moduliConsentiti as $key=>$modulo): ?>
                    <option value="<?php echo $key ?>"<?php if (implode("-",$this->modulo) == $key) echo ' selected="selected"'; ?>><?php echo $modulo ?></option>
                <?php endforeach; ?>
            </select>
        </fieldset>
        <fieldset id="cloni" class="hidden">
            <?php foreach($this->modulo as $key => $numGiocatori): ?>
                <div class="<?php echo $key; ?>">
                    <div class="form-group">
                        <select class="form-control">
                            <option></option>
                            <?php foreach ($this->giocatori[$key] as $key2 => $giocatore): ?>
                                <option value="<?php echo $key2 ?>" ><?php echo $giocatore ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endforeach; ?>
        </fieldset>
        <fieldset id="titolari-field">
            <?php $j = 0; foreach($this->modulo as $key => $numGiocatori): ?>
                <h4><?php echo $this->ruoli[$key]->plurale ?></h4>
                <div class="<?php echo $key; ?>">
                    <?php for ($i = 0; $i < $numGiocatori; $i++): ?>
                        <div class="form-group">
                            <select class="form-control" id="gioc-<?php echo $j; ?>" name="titolari[<?php echo $j; ?>]">
                                <option></option>
                                <?php foreach ($this->giocatori[$key] as $key2 => $giocatore): ?>
                                    <option value="<?php echo $key2 ?>" <?php if (isset($this->formazione->giocatori[$j]) && $this->formazione->giocatori[$j]->idGiocatore == $key2) echo ' selected="selected"'; ?>><?php echo $giocatore ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php $j++; endfor; ?>
                </div>
            <?php endforeach; ?>
        </fieldset>
        <fieldset id="panchina-field">
            <h4>Panchinari</h4>
            <?php for ($i = 0; $i < 7; $i++): ?>
                <div class="form-group">
                    <select class="form-control" id="panchField-<?php echo $i; ?>" name="panchinari[<?php echo $i; ?>]">
                        <option></option>
                        <?php foreach ($this->giocatori as $ruolo => $giocatori): ?>
                            <optgroup label="<?php echo $this->ruoli[$ruolo]->plurale; ?>">
                                <?php foreach ($giocatori as $key => $giocatore): ?>
                                    <option value="<?php echo $key ?>" <?php if (isset($this->formazione->giocatori[$i + 11]) && $this->formazione->giocatori[$i + 11]->idGiocatore == $key) echo ' selected="selected"'; ?>><?php echo $giocatore ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endfor; ?>
        </fieldset>
        <fieldset id="capitani-field">
            <h4>Capitani</h4>
            <?php foreach($this->capitani as $capitano): ?>
                <div class="form-group">
                    <select id="capField-<?php echo $capitano ?>" class="form-control" name="formazione[<?php echo $capitano; ?>]">
                        <option></option>
                        <?php foreach ($this->giocatori as $ruolo => $giocatori): ?>
                            <?php if($ruolo == "P" || $ruolo == "D"): ?>
                                <?php foreach ($giocatori as $key => $giocatore): ?>
                                    <option value="<?php echo $key ?>" <?php if (isset($this->formazione) && $this->formazione->$capitano == $key) echo ' selected="selected"'; ?>><?php echo $giocatore ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endforeach; ?>
        </fieldset>
        <fieldset>
            <?php if ($_SESSION['datiLega']->jolly && (!$this->usedJolly || $this->formazione->getJolly())): ?>
                <div class="checkbox-inline">
                    <label for="jolly">
                        <input type="checkbox" value="1" name="formazione[jolly]" id="jolly" <?php if (!is_null($this->formazione) && $this->formazione->getJolly()) echo ' checked="checked"'; ?> /> Jolly
                    </label>
                </div>
            <?php endif; ?>
            <?php if ($this->giornata == $this->currentGiornata && $this->squadra == $_SESSION['idUtente']): ?>
                <input name="submit" type="submit" class="btn btn-primary" value="Invia" />
            <?php endif; ?>
        </fieldset>
    </form>
</div>
<?php else: ?>
    <p>La stagione è finita. Non puoi settare la formazione ora</p>
<?php endif; ?>
