<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Score $score
 */
?>
<?php if(!empty($ranking)): ?>
<div id="ranking-container" data-squadra="">
    <table class="mdl-data-table mdl-js-data-table mdl-shadow--4dp">
        <thead>
            <tr>
                <th>P.</th>
                <th class="mdl-data-table__cell--non-numeric">Nome</th>
                <th class="nowrap text-right"><abbr title="Punti totali">PTot</abbr></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ranking as $key => $score):  ?>
                <tr>
                    <td><?php echo $key++ + 1; ?></td>
                    <td class="mdl-data-table__cell--non-numeric" id="squadra-<?php echo $score->team->id; ?>">
                        <a href="<?php echo $this->Url->build(['controller' => 'Teams', 'action' => 'view', $score->team->id]) ?>"><?php echo $score->team->name; ?></a>
                    </td>
                    <td class="text-right"><?php echo $score->sum_points; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (!empty($scores)): ?>
        <div id="tab_classifica">
            <table class="mdl-data-table mdl-js-data-table mdl-shadow--4dp">
                <thead>
                    <tr>
                        <?php foreach (array_pop($scores) as $key => $score): ?>
                            <th><?php echo $score->matchday->number; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scores as $team => $stat): ?>
                        <tr>
                            <?php foreach ($stat as $matchday => $score): ?>
                                <td class="<?php echo ($score->penality_points > 0) ? "rosso" : ''; ?>" title="<?php echo ($score->penality_points > 0) ? ' Penalità: ' . $score->penality_points . ' punti' : ''; ?>">
                                    <a href="<?php echo $this->Url->build(['controller' => 'Scores', 'action' => 'view', $score->id]); ?>"><?php echo $score->real_points; ?></a>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php if (!empty($this->giornate)): ?>
    <div id="grafico" class="hidden-xs">
        <div id="placeholder" style="height:300px"></div>
        <div id="overview" style="width:200px;height:100px"></div>
        <p>Seleziona sulla miniatura una parte di grafico per ingrandirla.</p>
        <p id="selection">&nbsp;</p>
        <a id="clear-selection" class="btn btn-danger">Cancella selezione</a>
    </div>
<?php endif; ?>