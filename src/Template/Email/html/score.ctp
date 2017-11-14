<html>
    <body style="font-family:'Trebuchet MS',Arial,sans-serif;overflow:hidden">
        <h2>
            <a style="color:#00a2ff;text-decoration:none;" title="Home">FantaManajer</a>
        </h2>
        <div>
            <h3>
                <a style="color:#00a2ff;text-decoration:none;" >Punteggio: <?php echo $score->points; ?></a>
            </h3>
            <?= $this->element('lineups', ['dispositions' => $regulars, 'caption' => __('Titolari'), 'full' => true]) ?>
            <?= $this->element('lineups', ['dispositions' => $notRegulars, 'caption' => __('Panchinari'), 'full' => true]) ?>
            <?php if ($ranking): ?>
                <div>
                    <table width="300">
                        <tbody>
                            <tr>
                                <th></th>
                                <th>Squadra</th>
                                <th>P.ti</th>
                            </tr>
                            <?php foreach ($ranking as $key => $score): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td class="mdl-data-table__cell--non-numeric">
                                        <a href="<?php echo $this->Url->build(['controller' => 'Teams', 'action' => 'view', $score->team->id], true) ?>"><?php echo $score->team->name; ?></a>
                                    </td>
                                    <td class="text-right"><?php echo $score->sum_points; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>

