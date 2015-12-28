<form class="form-inline" action="<?php echo $this->router->generate('members_free'); ?>" method="post">
    <fieldset>
        <div class="mdl-textfield mdl-js-textfield">
            <select class="mdl-textfield__select" id="role" name="role">
                <?php foreach ($this->roles as $key => $role): ?>
                    <option<?php echo ($this->role == $role) ? ' selected="selected"' : ''; ?> value="<?php echo $key ?>"><?php echo $role->singolar; ?></option>
                <?php endforeach ?>
            </select>
            <label for="role" class="mdl-textfield__label">Ruolo:</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input pattern="-?[0-9]*(\.[0-9]+)?" class="mdl-textfield__input small" id="enough" maxlength="3" name="enough" type="text" value="<?php if ($this->validFilter) echo $this->defaultEnough; ?>" />
            <label class="mdl-textfield__label" for="enough">Soglia sufficienza</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input pattern="-?[0-9]*(\.[0-9]+)?" class="mdl-textfield__input small" id="match" maxlength="2" name="match" type="text" value="<?php if ($this->validFilter) echo $this->defaultMatch; ?>" />
            <label class="mdl-textfield__label" for="match">Soglia partite</label>
        </div>
        <input class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" type="submit" value="OK"/>
    </fieldset>
</form>
<?php if ($_SESSION['logged']): ?>
    <form action="<?php echo $this->router->generate('teams_show', array('id' => $_SESSION['team']->id)) . "#tab-transfert"; ?>" method="post">
    <?php endif; ?>
    <table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--4dp table-sorter">
        <thead>
            <tr>
                <th class="mdl-data-table__cell--non-numeric">Nome</th>
                <th class="mdl-data-table__cell--non-numeric xidden-xs">Club</th>
                <th>Partite</th>
                <th><abbr title="Media voti">MV</abbr></th>
                <th><abbr title="Media punti">MP</abbr></th>
                <?php if ($this->role->abbreviation == 'P'): ?><th><abbr title="Gol subiti">GS</abbr></th><?php endif; ?>
                <?php if ($this->role->abbreviation != 'P'): ?><th>Gol</th><?php endif; ?>
                <th class="hidden-xs">Assist</th>
                <th class="hidden-xs"><abbr title="Ammonito"><i class="ammonizione"></i></abbr></th>
                <th class="hidden-xs"><abbr title="Espulso"><i class="espulsione"></i></abbr></th>
                <th class="hidden-xs"><abbr title="Quotazione">Quot.</abbr></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->freePlayers as $member): ?>
                <tr>
                    <td class="mdl-data-table__cell--non-numeric"><a href="<?php echo $this->router->generate('members_show', array('id' => $member->getId())) ?>"><?php echo $member->player; ?></a></td>
                    <td class="mdl-data-table__cell--non-numeric hidden-xs"><?php echo strtoupper(substr($member->club, 0, 3)); ?></td>
                    <td>
                        <?php if ($member->sum_valued >= $this->defaultMatch && $this->currentMatchday->number != 1): ?>
                            <span class="mdl-badge ok" data-badge="✓">
                                <?php echo $member->sum_valued ?>
                            </span>
                        <?php else: ?>
                            <?php echo $member->sum_valued ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($member->avg_rating >= $this->defaultEnough && $this->currentMatchday->number != 1): ?>
                        <span class="mdl-badge ok" data-badge="✓">
                                <?php echo $member->avg_rating ?>
                            </span>
                        <?php else: ?>
                            <?php echo $member->avg_rating ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($member->avg_points >= $this->defaultEnough && $this->currentMatchday->number != 1): ?>
                        <span class="mdl-badge ok" data-badge="✓">
                                <?php echo $member->avg_points ?>
                            </span>
                        <?php else: ?>
                            <?php echo $member->avg_points ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo ($this->role->abbreviation == 'P') ? $member->sum_goals_against : $member->sum_goals ?></td>
                    <td class="hidden-xs"><?php echo $member->sum_assist ?></td>
                    <td class="hidden-xs"><?php echo $member->sum_yellow_card ?></td>
                    <td class="hidden-xs"><?php echo $member->sum_red_card ?></td>
                    <td class="hidden-xs"><?php echo $member->quotation ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (!$this->isSeasonEnded && $_SESSION['league_view'] == $_SESSION['team']->championship_id): ?>
        <p class="alert-message alert alert-info">Se clicchi sul bottone sottostante selezionerai il giocatore per l'acquisto che comunque non avverrà subito e che può essere annullato. Nella pagina che ti apparirà dopo aver cliccato sul bottone ci sono altre informazioni</p>
        <input type="submit" class="btn btn-primary" value="Acquista" />
    </fieldset>
    </form>
<?php endif; ?>
