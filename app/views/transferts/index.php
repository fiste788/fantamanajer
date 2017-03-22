<?php if (!empty($this->transferts)): ?>
    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th>N.</th>
                    <th>Giocatore nuovo</th>
                    <th>Giocatore vecchio</th>
                    <th>Giornata</th>
                    <th>Obbligato</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->transferts as $key => $transferts): ?>
                    <tr>
                        <td><?php echo $key + 1; ?></td>
                        <td><a href="<?php echo $this->router->generate('members_show', array('id' => $transferts->new_member_id)); ?>"><?php echo $val->getNewMember()->player; ?></a></td>
                        <td><a href="<?php echo $this->router->generate('members_show', array('id' => $transferts->old_member_id)); ?>"><?php echo $val->getOldMember()->player; ?></a></td>
                        <td><?php echo $transferts->matchday_id; ?></td>
                        <td><?php if ($transferts->isConstrainded()): ?><i class="icon-ok"></i><?php endif; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>Non ha effettuato alcun trasferimento</p>
<?php endif; ?>
<?php if ($_SESSION['logged'] && $_SESSION['team']->id == $this->filterId && count($this->transferts) < $_SESSION['championship_data']->number_transferts && $this->currentMatchday->number > 1 && !empty($this->players)): ?>
    <h3>Acquista un giocatore</h3>
    <p class="alert-block alert alert-info">Quì è possibile indicare il nome del giocatore che volete acquistare. Se il giocatore è stato già selezionato da una squadra inferiore alla tua in classifica allora riceverai un messaggio di errore.<br />Al contrario il giocatore sarà selezionato per la tua squadra.<br />Se il proprietario di una squadra inferiore alla tua seleziona il tuo stesso giocatore il giocatore diventerà suo e una mail ti avviserà dell'accaduto in modo da poter selezionare un nuovo giocatore.<br/>I trasferimenti saranno eseguiti nella nottata del giorno di fine di ogni giornata. E' possibile cambiare il giocatore selezionato 2 sole volte.</p>
    <form action="<?php echo $this->router->generate('selection_update', array('team_id' => $_SESSION['team']->id)); ?>" method="post">
        <fieldset>
            <div class="form-group">
                <label for="player-old">Giocatore vecchio:</label>
                <select class="form-control" id="player-old" name="selection[old_member_id]">
                    <option></option>
                    <?php foreach ($this->roles as $keyRole => $role): ?>
                        <optgroup label="<?php echo $role->plural; ?>">
                            <?php foreach ($this->players as $key => $member): ?>
                                <?php if ($member->role->id == $keyRole): ?>
                                    <option value="<?php echo $member->id; ?>"<?php echo (isset($this->selection->old_member_id) && $this->selection->old_member_id == $member->id) ? ' selected="selected"' : ''; ?>><?php echo $member->player ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="player-new">Giocatore nuovo:</label>
                <select class="form-control" id="player-new" name="selection[new_member_id]">
                    <option></option>
                    <?php foreach ($this->roles as $keyRole => $role): ?>
                        <optgroup label="<?php echo $role->plural; ?>">
                            <?php foreach ($this->freePlayers as $key => $member): ?>
                                <?php if ($member->role->id == $keyRole): ?>
                                    <option value="<?php echo $member->id; ?>"<?php echo (isset($this->selection->new_member_id) && $this->selection->new_member_id == $member->id) ? '  selected="selected"' : ''; ?>><?php echo $member->player ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
        </fieldset>
        <fieldset>
            <input class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" type="submit" name="submit" value="OK" />
            <?php if (!is_null($this->selection)): ?>
                <input id="btn-canc" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored" type="reset" name="submit" value="Cancella acq." />
            <?php endif; ?>
        </fieldset>
    </form>
<?php endif; ?>