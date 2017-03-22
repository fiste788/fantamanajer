<?php if (!$this->currentMatchday->isSeasonEnded() || $this->matchday->id != $this->currentMatchday->id): ?>
<form action="<?php echo $this->router->generate('lineups'); ?>" method="post">
    <div id="advanced" class="hidden-sm hidden-xs">
        <?php if(!empty($this->players)): ?>
            <div id="players" class="clearfix"<?php if ($this->team->id != $_SESSION['team']->id) echo 'data-hidden="true"'; ?>>
                <?php foreach ($this->players as $role => $members): ?>
                    <div class="ruoli <?php echo $role ?>">
                        <?php foreach ($members as $member): ?>
                            <div id="<?php echo $member->id; ?>"  data-role="<?php echo $this->roles[$role]->abbreviation ?>" data-surname="<?php echo $member->player->surname; ?>" class="draggable player <?php echo $this->roles[$role]->abbreviation; ?>">
                                <?php if (file_exists(PLAYERSDIR . $member->id . '.jpg')): ?>
                                    <img class="img-responsive" alt="<?php echo $member->id; ?>" src="<?php echo PLAYERSURL . $member->id; ?>.jpg" />
                                <?php endif; ?>
                                <p><?php echo $member->player->surname . ' ' . $member->player->name; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <h3>Giornata <?php echo $this->matchday->number; ?></h3>
        <div id="arena" class="clearfix">
            <div id="field" data-edit="<?php echo($_SESSION['team']->id == $this->team->id && $this->currentMatchday->id == $this->matchday->id) ? "true" : "false" ?>" data-modulo="<?php echo htmlspecialchars(json_encode($this->module)); ?>">
                <div id="P" class="droppable"></div>
                <div id="D" class="droppable"></div>
                <div id="C" class="droppable"></div>
                <div id="A" class="droppable"></div>
            </div>
            <div id="right">
                <div id="captains">
                    <h3>Capitani</h3>
                    <div id="idCapitano" class="droppable" data-symbol="C"></div>
                    <div id="idVCapitano" class="droppable" data-symbol="VC"></div>
                    <div id="idVVCapitano" class="droppable" data-symbol="VVC"></div>
                </div>
                <?php if ($_SESSION['championship_data']->jolly && (!$this->usedJolly || $this->lineup->getJolly())): ?>
                    <div class="checkbox-inline">
                        <label for="jolly">
                            <input type="checkbox" value="1" name="lineup[jolly]" id="jolly" <?php if (!is_null($this->lineup) && $this->lineup->getJolly()) echo ' checked="checked"'; ?> /> Jolly
                        </label>
                    </div>
                <?php endif; ?>
                <?php if ($this->matchday->id == $this->currentMatchday->id && $this->team->id == $_SESSION['team']->id): ?>
                    <input name="submit" type="submit" class="btn btn-primary" value="Invia" />
                <?php endif; ?>
                <div id="bench">
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
                <?php if(!isset($this->module)): ?><option></option><?php endif; ?>
                <?php foreach($this->moduleAllowed as $key=>$module): ?>
                    <option value="<?php echo $key ?>"<?php if (implode("-",$this->module) == $key) echo ' selected="selected"'; ?>><?php echo $module ?></option>
                <?php endforeach; ?>
            </select>
        </fieldset>
        <fieldset id="cloni" class="hidden">
            <?php foreach($this->module as $role => $numGiocatori): ?>
                <div class="<?php echo $this->roles[$role + 1]->abbreviation; ?>">
                    <div class="form-group">
                        <select class="form-control">
                            <option></option>
                            <?php foreach ($this->players[$role + 1] as $key2 => $member): ?>
                                <option value="<?php echo $key2 ?>" ><?php echo $member->player ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endforeach; ?>
        </fieldset>
        <fieldset id="regular-field">
            <?php $j = 0; foreach($this->module as $role => $numGiocatori): ?>
                <h4><?php echo $this->roles[$role + 1]->plural ?></h4>
                <div class="<?php echo $this->roles[$role + 1]->abbreviation; ?>">
                    <?php for ($i = 0; $i < $numGiocatori; $i++): ?>
                        <div class="form-group">
                            <select class="form-control" id="gioc-<?php echo $j; ?>" name="regular[<?php echo $j; ?>]">
                                <option></option>
                                <?php foreach ($this->players[$role + 1] as $key2 => $member): ?>
                                    <option value="<?php echo $key2 ?>" <?php if (isset($this->lineup->players[$j]) && $this->lineup->players[$j]->id == $key2) echo ' selected="selected"'; ?>><?php echo $member->player ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php $j++; endfor; ?>
                </div>
            <?php endforeach; ?>
        </fieldset>
        <fieldset id="bench-field">
            <h4>Panchinari</h4>
            <?php for ($i = 0; $i < 7; $i++): ?>
                <div class="form-group">
                    <select class="form-control" id="panchField-<?php echo $i; ?>" name="notRegular[<?php echo $i; ?>]">
                        <option></option>
                        <?php foreach ($this->players as $role => $members): ?>
                            <optgroup label="<?php echo $this->roles[$role]->plural; ?>">
                                <?php foreach ($members as $key => $member): ?>
                                    <option value="<?php echo $key ?>" <?php if (isset($this->lineup->players[$i + 11]) && $this->lineup->players[$i + 11]->id == $key) echo ' selected="selected"'; ?>><?php echo $member->player ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endfor; ?>
        </fieldset>
        <fieldset id="capitains-field">
            <h4>Capitani</h4>
            <?php foreach($this->captains as $captain): ?>
                <div class="form-group">
                    <select id="capField-<?php echo $captain ?>" class="form-control" name="lineup[<?php echo $captain; ?>]">
                        <option></option>
                        <?php foreach ($this->players as $role => $members): ?>
                            <?php if($role == "P" || $role == "D"): ?>
                                <?php foreach ($members as $key => $member): ?>
                                    <option value="<?php echo $key ?>" <?php if (isset($this->lineup) && $this->lineup->$captain == $key) echo ' selected="selected"'; ?>><?php echo $member->player ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endforeach; ?>
        </fieldset>
        <fieldset>
            <?php if ($_SESSION['championship_data']->jolly && (!$this->usedJolly || $this->lineup->getJolly())): ?>
                <div class="checkbox-inline">
                    <label for="jolly">
                        <input type="checkbox" value="1" name="lineup[jolly]" id="jolly" <?php if (!is_null($this->lineup) && $this->lineup->getJolly()) echo ' checked="checked"'; ?> /> Jolly
                    </label>
                </div>
            <?php endif; ?>
            <?php if ($this->matchday->id == $this->currentMatchday->id && $this->team->id == $_SESSION['team']->id): ?>
                <input name="submit" type="submit" class="btn btn-primary" value="Invia" />
            <?php endif; ?>
        </fieldset>
    </form>
</div>
<?php else: ?>
    <p>La stagione è finita. Non puoi settare la formazione ora</p>
<?php endif; ?>
