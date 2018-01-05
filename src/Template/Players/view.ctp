<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Player $player
 * @var \App\Model\Entity\Rating $rating
 */
?>
<div data-enllax-ratio="0.5" class="enllax-container" style="background-image:url('../img/clubs/bg/<?= $currentMember->club->id ?>.jpg')"></div>
<div class="title">
    <div class="photo-crop">
        <?php if(file_exists(WWW_ROOT . 'img' . DS . 'players' . DS . 'season-' . $currentMember->season_id . DS . $currentMember->code_gazzetta . '.jpg')): ?>
            <?= $this->Html->image('players/season-' . $currentMember->season_id . '/' . $currentMember->code_gazzetta . '.jpg', ['alt' => $player->fullname]); ?>
        <?php else: ?>
            <i class="material-icons md-light md-128">face</i>
        <?php endif; ?>
    </div>
    <h3><?= $player->fullName; ?></h3>
    <p>
        <?= $currentMember->role->singolar; ?> / 
        <?= $this->Html->link($currentMember->club->name,['controller' => 'Clubs', 'action' => 'view', $currentMember->club->id]) ?>
    </p>
</div>
<div class="mdl-container">
    <?php if (isset($currentMember->ratings) && !empty($currentMember->ratings)): ?>
        <section>
            <table id="matchdays" class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--4dp" data-member="<?php echo $currentMember; ?>">
                <thead>
                    <tr>
                        <th class="mdl-data-table__cell--non-numeric"><abbr title="Giornata">Giorn</abbr></th>
                        <th>Punti</th>
                        <th>Voti</th>
                        <th<?php if ($currentMember->role->abbreviation == "P") echo ' class="hidden-xxs"' ?>>Gol</th>
                        <th<?php if ($currentMember->role->abbreviation != "P") echo ' class="hidden-xxs"' ?>><abbr title="Gol subiti">Gol S</abbr></th>
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
                    <?php foreach ($currentMember->ratings as $key => $rating): ?>
                        <tr data-voto="[<?= $rating->matchday->number . "," . $rating->rating; ?>]" data-punti="[<?php echo $rating->matchday->number . "," . $rating->points ?>]">
                            <td class="mdl-data-table__cell--non-numeric"><?= $rating->matchday->number ?></td>
                            <td class="points"><?= $rating->points; ?></td>
                            <td class="rating"><?php echo ($rating->rating != '0') ? $rating->rating : "&nbsp;"; ?></td>
                            <td<?php if ($currentMember->role->abbreviation == "P") echo ' class="hidden-xxs"' ?>><?= $rating->goals ?></td>
                            <td<?php if ($currentMember->role->abbreviation != "P") echo ' class="hidden-xxs"' ?>><?= $rating->goalsAgainst ?></td>
                            <td><?= $rating->assist ?></td>
                            <td class="hidden-xxs"><?= $rating->penalitiesScored ?></td>
                            <td class="hidden-xxs"><?= $rating->penalitiesTaken ?></td>
                            <td><?php if ($rating->yellowCard): ?><i class="material-icons">check</i><?php endif; ?></td>
                            <td><?php if ($rating->redCard): ?><i class="material-icons">check</i><?php endif; ?></td>
                            <td><?php if ($rating->regular): ?><i class="material-icons">check</i><?php endif; ?></td>
                            <td class="hidden-xxs"><?= $rating->quotation ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="mdl-data-table__cell--non-numeric">Totali</td>
                        <td><?php echo (!empty($currentMember->avg_points)) ? $this->member->avg_points : ''; ?></td>
                        <td><?php echo (!empty($currentMember->avg_rating)) ? $this->member->avg_rating : ''; ?></td>
                        <td<?php if ($currentMember->role->abbreviation == "P") echo ' class="hidden-xxs"' ?>><?php echo $currentMember->sum_goals ?></td>
                        <td<?php if ($currentMember->role->abbreviation != "P") echo ' class="hidden-xxs"' ?>><?php echo $currentMember->sum_goals_against ?></td>
                        <td><?php echo $currentMember->sum_assist ?></td>
                        <td colspan="6"></td>
                    </tr>
                </tfoot>
            </table>
        </section>
    <?php endif; ?>
</div>