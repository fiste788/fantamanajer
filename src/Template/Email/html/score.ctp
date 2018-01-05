<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Score $score
 */
?>
<h3>
    <a href="<?php echo $baseUrl . '/scores/' . $score->id ?>" style="text-decoration:none;color:#ff4081">Punteggio: <?php echo $score->points; ?></a>
</h3>
<?= $this->element('Email/lineups', ['dispositions' => $regulars, 'caption' => __('Titolari'), 'full' => true, 'baseUrl' => $baseUrl]) ?>
<?= $this->element('Email/lineups', ['dispositions' => $notRegulars, 'caption' => __('Panchinari'), 'full' => true, 'baseUrl' => $baseUrl]) ?>
<?php if ($ranking): ?>
    <div>
        <table width="100%">
            <tbody>
                <tr>
                    <th></th>
                    <th>Squadra</th>
                    <th>P.ti</th>
                </tr>
                <?php foreach ($ranking as $key => $score): ?>
                <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td>
                        <a href="<?php echo $baseUrl . '/teams/' . $score->team->id ?>"><?php echo $score->team->name; ?></a>
                    </td>
                    <td><?php echo $score->sum_points; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
       