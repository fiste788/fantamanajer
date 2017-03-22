<?php if ($this->regular != NULL): ?>
    <h4>Punteggio: <span><?php echo (isset($this->score->real_points)) ? $this->score->real_points : ''; ?></span></h4>
    <?php if (!is_null($this->score->penality)): ?>
        <div class="alert alert-error">
            <img class="" alt="!" src="<?php echo IMGSURL . 'penalita.png'; ?>" />
            <div class="penality">
                <h5>Penalità: <?php echo $this->score->penality_points; ?><br />Motivazione: <?php echo $this->score->penality; ?></h5>
            </div>
        </div>
    <?php endif; ?>

    <table class="mdl-data-table mdl-js-data-table mdl-shadow--4dp">
        <caption>Titolari</caption>
        <thead>
            <tr>
                <th class="mdl-data-table__cell--non-numeric">Nome</th>
                <th class="mdl-data-table__cell--non-numeric">Ruolo</th>
                <th class="mdl-data-table__cell--non-numeric">Club</th>
                <th class="hidden-xs"><abbr title="Ammonito">Amm</abbr></th>
                <th class="hidden-xs"><abbr title="Espulso">Esp</abbr></th>
                <th class="hidden-xs"><abbr title="Titolare">Tit</abbr></th>
                <th class="hidden-xs">Assist</th>
                <th class="hidden-xs">Gol</th>
                <th>Punti</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->regular as $key => $member): ?>
                <tr<?php echo ($member->disposition->consideration == 0) ? ' class="alert-danger"' : '' ?>>
                    <td class="mdl-data-table__cell--non-numeric"><a class="mdl-navigation__link" href="<?php echo $this->router->generate('members_show', array('id' => $member->id)); ?>"><?php echo ($member->disposition->consideration == 2) ? $member->player . '<span id="cap">(C)</span>' : $member->player; ?></a></td>
                    <td class="mdl-data-table__cell--non-numeric"><?php echo $member->role->abbreviation; ?></td>
                    <td class="mdl-data-table__cell--non-numeric"><?php echo strtoupper(substr($member->club->name, 0, 3)); ?></td>
                    <td class="hidden-xs"><?php if ($member->isYellowCard()): ?><i class="material-icons">check</i><?php endif; ?></td>
                    <td class="hidden-xs"><?php if ($member->isRedCard()): ?><i class="material-icons">check</i><?php endif; ?></td>
                    <td class="hidden-xs"><?php if ($member->isRegular()): ?><i class="material-icons">check</i><?php endif; ?></td>
                    <td class="hidden-xs"><?php echo ($member->assist != 0) ? $member->assist : ""; ?></td>
                    <td class="hidden-xs"><?php echo ($member->goals != 0) ? $member->goals : ""; ?></td>
                    <td><?php if (!empty($member->points)) echo ($member->disposition->consideration == '2') ? $member->points * 2 : $member->points; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (!empty($this->notRegular)): ?>
        <table class="mdl-data-table mdl-js-data-table mdl-shadow--4dp">
            <caption>Panchinari</caption>
            <thead>
                <tr>
                    <th class="mdl-data-table__cell--non-numeric">Nome</th>
                    <th class="mdl-data-table__cell--non-numeric">Ruolo</th>
                    <th class="mdl-data-table__cell--non-numeric">Club</th>
                    <th class="hidden-xs"><abbr title="Ammonito">Amm</abbr></th>
                    <th class="hidden-xs"><abbr title="Espulso">Esp</abbr></th>
                    <th class="hidden-xs"><abbr title="Titolare">Tit</abbr></th>
                    <th class="hidden-xs">Assist</th>
                    <th class="hidden-xs">Gol</th>
                    <th>Punti</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->notRegular as $key => $member): ?>
                    <tr<?php echo ($member->disposition->consideration == 1) ? ' class="alert-success"' : '' ?>>
                    <td class="mdl-data-table__cell--non-numeric"><a class="mdl-navigation__link" href="<?php echo $this->router->generate('members_show', array('id' => $member->id)); ?>"><?php echo ($member->disposition->consideration == 2) ? $member->player . '<span id="cap">(C)</span>' : $member->player; ?></a></td>
                    <td class="mdl-data-table__cell--non-numeric"><?php echo $member->role->abbreviation; ?></td>
                    <td class="mdl-data-table__cell--non-numeric"><?php echo strtoupper(substr($member->club->name, 0, 3)); ?></td>
                    <td class="hidden-xs"><?php if ($member->isYellowCard()): ?><i class="material-icons">check</i><?php endif; ?></td>
                    <td class="hidden-xs"><?php if ($member->isRedCard()): ?><i class="material-icons">check</i><?php endif; ?></td>
                    <td class="hidden-xs"><?php if ($member->isRegular()): ?><i class="material-icons">check</i><?php endif; ?></td>
                    <td class="hidden-xs"><?php echo ($member->assist != 0) ? $member->assist : ""; ?></td>
                    <td class="hidden-xs"><?php echo ($member->goals != 0) ? $member->goals : ""; ?></td>
                    <td><?php echo $member->points; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
<?php endif; ?>