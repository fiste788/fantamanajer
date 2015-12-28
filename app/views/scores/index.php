<?php $i = 1; if(!empty($this->ranking)): ?>
<div id="ranking-container" data-squadra="<?php echo ($_SESSION['logged']) ? $_SESSION['team']->id : 'false'; ?>">
    <table class="mdl-data-table mdl-js-data-table mdl-shadow--4dp">
        <thead>
            <tr>
                <th>P.</th>
                <th class="nowrap">Nome</th>
                <th class="nowrap text-right"><abbr title="Punti totali">PTot</abbr></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->ranking as $key => $val): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td class="mdl-data-table__cell--non-numeric" id="squadra-<?php echo $key; ?>"><a href="<?php echo $this->router->generate('teams_show', array('id' => $key)) ?>"><?php echo $this->teams[$key]->name; ?></a></td>
                    <td class="text-right"><?php echo $val->sum_points; ?></td>
                </tr>
            <?php $flag = $key; endforeach; ?>
        </tbody>
    </table>
    <?php if (!empty($this->ranking[$flag]->details)): ?>
        <div id="tab_classifica">
            <table class="mdl-data-table mdl-js-data-table mdl-shadow--4dp">
                <thead>
                    <tr>
                        <?php foreach ($this->ranking[$flag]->details as $key => $val): ?>
                            <th class="<?php if((count($this->ranking[$flag]) - $key) > 1) echo ' hidden-xs';if((count($this->ranking[$flag]) - $key) > 4) echo ' hidden-phone';if((count($this->ranking[$flag]) - $key) > 9) echo ' hidden-tablet'; ?>"><?php echo $key; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->ranking as $team => $stat): ?>
                        <tr data-media="<?php echo $stat->avg_points ?>" data-key="<?php echo $team; ?>" data-squadra="<?php echo trim($this->teams[$team]->name); ?>">
                            <?php foreach ($stat->details as $matchday => $score): ?>
                                <td class="<?php if((count($this->ranking[$flag]) - $matchday) > 1) echo ' hidden-xs';if((count($this->ranking[$flag]) - $matchday) > 4) echo ' hidden-phone';if((count($this->ranking[$flag]) - $matchday) > 9) echo ' hidden-tablet'; echo ($score->penality_points > 0) ? "rosso" : ''; ?>" title="<?php echo ($score->penality_points > 0) ? ' Penalità: ' . $score->penality_points . ' punti' : ''; ?>">
                                    <a href="<?php echo $this->router->generate('scores_show', array('matchday_id' => $matchday, 'team_id' => $team)); ?>"><?php echo $score->real_points; ?></a>
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
