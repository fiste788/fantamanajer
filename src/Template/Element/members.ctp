<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Member[]|\Cake\Collection\CollectionInterface $members
 */
?>
<?php if (!empty($members)): ?>
    <section>
        <table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--4dp">
            <thead>
                <tr>
                    <th class="mdl-data-table__cell--non-numeric">Nome</th>
                    <th class="mdl-data-table__cell--non-numeric">Ruolo</th>
                    <?php if($showClub): ?>
                        <th class="mdl-data-table__cell--non-numeric">Club</th>
                    <?php endif; ?>
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
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td class="mdl-data-table__cell--non-numeric">
                            <?= $this->Html->link(h($member->player->fullName),['controller'=>'Players','action'=>'view',$member->player->id],['class'=>'mdl-navigation__link']) ?>
                        </td>
                        <td class="mdl-data-table__cell--non-numeric"><?php echo $member->role->abbreviation; ?></td>
                        <?php if($showClub): ?>
                            <td class="mdl-data-table__cell--non-numeric">
                                <a href="<?= $this->Url->build(['controller' => 'Clubs', 'action' => 'view', $member->club->id]) ?>">
                                    <?= $this->Html->image('clubs/' . $member->club->id . '.png',['height'=> 32, 'alt' => $member->club->abbreviation]) ?>
                                </a>
                            </td>
                        <?php endif; ?>
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
        </table>
    </section>
<?php endif; ?>