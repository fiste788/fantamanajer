<div class="mdl-container">
    <section class="mdl-grid mdl-grid--no-spacing mdl-shadow--4dp">
        <header class="section__play-btn mdl-cell mdl-cell--3-col-desktop mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-color--teal-100 mdl-color-text--white">
            <?php if (file_exists(UPLOADDIR . 'thumb/' . $this->team->id . '.jpg')): ?>
                <a title="<?php echo $this->team ?>" href="<?php echo UPLOADURL . $this->team->id . '.jpg'; ?>" class="fancybox logo pull-left">
                    <img class="img-thumbnail" alt="<?php echo $this->team->id; ?>" src="<?php echo UPLOADURL . 'thumb/' . $this->team->id . '.jpg'; ?>" title="Logo <?php echo $this->team ?>" />
                </a>
            <?php endif; ?>
        </header>
        <div class="mdl-card mdl-cell mdl-cell--9-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
            <div class="mdl-card__title">
                <h3 class="mdl-card__title-text"><?php echo $this->team->name; ?></h3>
            </div>
            <div class="mdl-card__supporting-text">
                <span class="bold">Username:</span><?php echo $this->team->user->username; ?>
            </div>
        </div>
    </section>
    <section class="mdl-layout__tab-panel is-active" id="tab_players">
        <?php if (!empty($this->members)): ?>
            <table class="mdl-data-table mdl-js-data-table mdl-shadow--4dp">
                <thead>
                    <tr>
                        <th class="mdl-data-table__cell--non-numeric">Nome</th>
                        <th class="mdl-data-table__cell--non-numeric">Ruolo</th>
                        <th><abbr title="Partite giocate">PG</abbr></th>
                        <th><abbr title="Media voto">MV</abbr></th>
                        <th><abbr title="Media punti">MP</abbr></th>
                        <th class="hidden-xs">Gol</th>
                        <th class="hidden-xs">Gol subiti</th>
                        <th class="hidden-xs">Assist</th>
                        <th class="hidden-xs"><abbr title="Ammonito"><i class="ammonizione"></i></abbr></th>
                        <th class="hidden-xs"><abbr title="Espulso"><i class="espulsione"></i></abbr></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->members as $member): ?>
                        <tr>
                            <td class="mdl-data-table__cell--non-numeric">
                                <a href="<?php echo $this->router->generate("members_show", array('id' => $member->id)); ?>"><?php echo $member->player->surname . ' ' . $member->player->name; ?></a>
                            </td>
                            <td class="mdl-data-table__cell--non-numeric"><?php echo $member->role->abbreviation; ?></td>
                            <td><?php echo $member->sum_present . " (" . $member->sum_valued . ")"; ?></td>
                            <td><?php echo $member->avg_rating ?></td>
                            <td><?php echo $member->avg_points ?></td>
                            <td class="hidden-xs"><?php echo $member->sum_goals ?></td>
                            <td class="hidden-xs"><?php echo $member->sum_goals_against ?></td>
                            <td class="hidden-xs"><?php echo $member->sum_assist ?></td>
                            <td class="hidden-xs"><?php echo $member->sum_yellow_card ?></td>
                            <td class="hidden-xs"><?php echo $member->sum_red_card ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="mdl-data-table__cell--non-numeric" colspan="3">Totali</td>
                        <td><?php echo $this->team->avg_rating; ?></td>
                        <td><?php echo $this->team->avg_points; ?></td>
                        <td class="hidden-xs"><?php echo $this->team->sum_goals; ?></td>
                        <td class="hidden-xs"><?php echo $this->team->sum_goals_against; ?></td>
                        <td class="hidden-xs"><?php echo $this->team->sum_assist; ?></td>
                        <td class="hidden-xs"><?php echo $this->team->sum_yellow_card; ?></td>
                        <td class="hidden-xs"><?php echo $this->team->sum_red_card; ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php endif; ?>
    </section>
    <section class="mdl-layout__tab-panel" id="tab_transfert" data-remote="<?php echo $this->router->generate('transferts_index',['team_id' => $this->team->id]) ?>">
        <div class="mdl-spinner mdl-js-spinner is-active"></div>
    </section>
    <section class="mdl-layout__tab-panel" id="tab_articles" data-remote="<?php echo $this->router->generate('team_articles',['team_id' => $this->team->id]) ?>">
        <div class="mdl-spinner mdl-js-spinner is-active"></div>
    </section>
    <section class="mdl-layout__tab-panel" id="tab_last_score" data-remote="<?php echo $this->router->generate('scores_show',['team_id' => $this->team->id]) ?>">
        <div class="mdl-spinner mdl-js-spinner is-active"></div>
    </section>
</div>