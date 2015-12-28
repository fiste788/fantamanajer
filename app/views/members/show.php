<div class="mdl-container">
    <section class="mdl-grid mdl-grid--no-spacing mdl-shadow--4dp">
        <header class="section__play-btn mdl-cell mdl-cell--3-col-desktop mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-color--teal-100 mdl-color-text--white">
            <img alt="<?php echo $this->member->player; ?>" src="<?php echo $this->pathPhoto ?>" />
        </header>
        <div class="mdl-card mdl-cell mdl-cell--9-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
            <div class="mdl-card__title">
                <h2 class="mdl-card__title-text"><?php echo $this->member->player; ?></h2>
            </div>
            <div class="mdl-card__supporting-text">
                <?php echo $this->member->role->singolar; ?><br/>
                <a href="<?php echo $this->router->generate("clubs_show", ["id" => $this->member->club->id]) ?>"><?php echo $this->member->club ?></a>
            </div>
        </div>
    </section>
    <?php if (isset($this->member->ratings) && !empty($this->member->ratings)): ?>
        <section>
            <table id="matchdays" class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--4dp" data-member="<?php echo $this->member; ?>">
                <thead>
                    <tr>
                        <th class="mdl-data-table__cell--non-numeric"><abbr title="Giornata">Giorn</abbr></th>
                        <th>Punti</th>
                        <th>Voti</th>
                        <th<?php if ($this->member->role->abbreviation == "P") echo ' class="hidden-xxs"' ?>>Gol</th>
                        <th<?php if ($this->member->role->abbreviation != "P") echo ' class="hidden-xxs"' ?>><abbr title="Gol subiti">Gol S</abbr></th>
                        <th>Assist</th>
                        <th class="hidden-xxs"><abbr title="Rigori">Rig</abbr></th>
                        <th class="hidden-xxs"><abbr title="Rigori subiti">Rig S</abbr></th>
                        <th><abbr title="Ammonito"><i class="ammonizione"></i></abbr></th>
                        <th><abbr title="Espulso"><i class="espulsione"></i></abbr></th>
                        <th><abbr title="Titolare">Tit</abbr></th>
                        <th class="hidden-xxs"><abbr title="Quotazione">Quot</abbr></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->member->ratings as $key => $rating): ?>
                        <tr data-voto="[<?php echo $rating->matchday->number . "," . $rating->getRating(); ?>]" data-punti="[<?php echo $rating->matchday->number . "," . $rating->getPoints(); ?>]">
                            <td class="mdl-data-table__cell--non-numeric"><?php echo $rating->matchday->number ?></td>
                            <td class="points"><?php echo $rating->getPoints(); ?></td>
                            <td class="rating"><?php echo ($rating->getRating() != '0') ? $rating->getRating() : "&nbsp;"; ?></td>
                            <td<?php if ($this->member->role->abbreviation == "P") echo ' class="hidden-xxs"' ?>><?php echo $rating->getGoals(); ?></td>
                            <td<?php if ($this->member->role->abbreviation != "P") echo ' class="hidden-xxs"' ?>><?php echo $rating->getGoalsAgainst(); ?></td>
                            <td><?php echo $rating->getAssist(); ?></td>
                            <td class="hidden-xxs"><?php echo $rating->getPenalitiesScored(); ?></td>
                            <td class="hidden-xxs"><?php echo $rating->getPenalitiesTaken(); ?></td>
                            <td><?php if ($rating->isYellowCard()): ?><i class="material-icons">check</i><?php endif; ?></td>
                            <td><?php if ($rating->isRedCard()): ?><i class="material-icons">check</i><?php endif; ?></td>
                            <td><?php if ($rating->isRegular()): ?><i class="material-icons">check</i><?php endif; ?></td>
                            <td class="hidden-xxs"><?php echo $rating->getQuotation(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="mdl-data-table__cell--non-numeric">Totali</td>
                        <td><?php echo (!empty($this->member->avg_points)) ? $this->member->avg_points : ''; ?></td>
                        <td><?php echo (!empty($this->member->avg_rating)) ? $this->member->avg_rating : ''; ?></td>
                        <td<?php if ($this->member->role->abbreviation == "P") echo ' class="hidden-xxs"' ?>><?php echo $this->member->sum_goals ?></td>
                        <td<?php if ($this->member->role->abbreviation != "P") echo ' class="hidden-xxs"' ?>><?php echo $this->member->sum_goals_against ?></td>
                        <td><?php echo $this->member->sum_assist ?></td>
                        <td colspan="6"></td>
                    </tr>
                </tfoot>
            </table>
        </section>
        <section>
            <div id="grafico" class="mdl-card mdl-shadow--4dp hidden-xs">
                <div id="placeholder" style="height:300px"></div>
                <div id="overview" style="width:200px;height:100px"></div>
                <p>Seleziona sulla miniatura una parte di grafico per ingrandirla.</p>
                <p id="selection">&nbsp;</p>
                <a id="clear-selection" class="btn btn-danger">Cancella selezione</a>
            </div>
        </section>
    <?php endif; ?>
</div>