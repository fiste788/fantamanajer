<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Member[]|\Cake\Collection\CollectionInterface $members
 * @var \App\Model\Entity\Role $role
 */
?>
<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
    <div class="mdl-tabs__tab-bar">
        <?php foreach($roles as $role): ?>
            <a href="<?= $this->Url->build(['controller' => 'Championships', 'action' => 'view','id' => $currentChampionship->id, 'role_id' => $role->id, '#' => 'free_players']) ?>"><?= $role->name ?></a>
        <?php endforeach; ?>
    </div>
    <table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--4dp table-sorter">
        <thead>
            <tr>
                <th class="mdl-data-table__cell--non-numeric">Nome</th>
                <th class="mdl-data-table__cell--non-numeric xidden-xs">Club</th>
                <th>Partite</th>
                <th><abbr title="Media voti">MV</abbr></th>
                <th><abbr title="Media punti">MP</abbr></th>
                    <?php if ($role->abbreviation == 'P'): ?><th><abbr title="Gol subiti">GS</abbr></th><?php endif; ?>
                    <?php if ($role->abbreviation != 'P'): ?><th>Gol</th><?php endif; ?>
                <th class="hidden-xs">Assist</th>
                <th class="hidden-xs"><abbr title="Ammonito"><i class="ammonizione"></i></abbr></th>
                <th class="hidden-xs"><abbr title="Espulso"><i class="espulsione"></i></abbr></th>
                <th class="hidden-xs"><abbr title="Quotazione">Quot.</abbr></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $member): ?>
            <tr>
                <td class="mdl-data-table__cell--non-numeric"><a href="<?= $this->Url->build(['controller' => 'Members', 'action' => 'view'], ['id' => $member->id]) ?>"><?php echo $member->player->fullName; ?></a></td>
                <td class="mdl-data-table__cell--non-numeric hidden-xs">
                    <a href="<?= $this->Url->build(['controller' => 'Clubs', 'action' => 'view', $member->club->id]) ?>">
                        <?= $this->Html->image('clubs/' . $member->club->id . '.png',['height'=> 32, 'alt' => $member->club->abbreviation]) ?>
                    </a>
                </td>
                <td><?php echo $member->stats->sum_valued ?></td>
                <td><?php echo $member->stats->avg_rating ?></td>
                <td><?php echo $member->stats->avg_points ?></td>
                <td><?php echo ($role->abbreviation == 'P') ? $member->stats->sum_goals_against : $member->stats->sum_goals ?></td>
                <td class="hidden-xs"><?php echo $member->stats->sum_assist ?></td>
                <td class="hidden-xs"><?php echo $member->stats->sum_yellow_card ?></td>
                <td class="hidden-xs"><?php echo $member->stats->sum_red_card ?></td>
                <td class="hidden-xs"><?php echo $member->stats->quotation ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
