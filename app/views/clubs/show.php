<div class="mdl-container">
    <section class="mdl-grid mdl-grid--no-spacing mdl-shadow--4dp">
        <header class="section__play-btn mdl-cell mdl-cell--3-col-desktop mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-color--teal-100 mdl-color-text--white">
            <img alt="<?php echo $this->club->id; ?>" src="<?php echo CLUBSURL . $this->club->id . '.png'; ?>" title="Logo <?php echo $this->club->name; ?>" />
        </header>
        <div class="mdl-card mdl-cell mdl-cell--9-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
            <div class="mdl-card__title">
                <h3 class="mdl-card__title-text"><?php echo $this->club->name; ?></h3>
            </div>
            <div class="mdl-card__supporting-text">
                Dolore ex deserunt aute fugiat aute nulla ea sunt aliqua nisi cupidatat eu. Nostrud in laboris labore nisi amet do dolor eu fugiat consectetur elit cillum esse.
            </div>
        </div>
    </section>
    <?php if (!empty($this->members)): ?>
        <section>
            <h3>Giocatori</h3>
            <table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--4dp">
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
                        <td><?php echo $this->club->avg_rating; ?></td>
                        <td><?php echo $this->club->avg_points; ?></td>
                        <td class="hidden-xs"><?php echo $this->club->sum_goals; ?></td>
                        <td class="hidden-xs"><?php echo $this->club->sum_goals_against; ?></td>
                        <td class="hidden-xs"><?php echo $this->club->sum_assist; ?></td>
                        <td class="hidden-xs"><?php echo $this->club->sum_yellow_card; ?></td>
                        <td class="hidden-xs"><?php echo $this->club->sum_red_card; ?></td>
                    </tr>
                </tfoot>
            </table>
        </section>
    <?php endif; ?>
</div>