<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Score $score
 * @var string $baseUrl
 * @var mixed $notRegulars
 * @var mixed $ranking
 * @var mixed $regulars
 */
?>
<h3>
    <a href="<?php echo $baseUrl . '/scores/' . $score->id ?>" style="text-decoration:none;color:#ff4081"><?= __('Score') ?>: <?php echo $score->points; ?></a>
</h3>
<?= $this->element('email/lineups', ['dispositions' => $regulars, 'caption' => __('Regular'), 'full' => true, 'baseUrl' => $baseUrl]) ?>
<?= $this->element('email/lineups', ['dispositions' => $notRegulars, 'caption' => __('Not regular'), 'full' => true, 'baseUrl' => $baseUrl]) ?>
<?php if ($ranking) : ?>
    <div>
        <table width="100%">
            <tbody>
                <tr>
                    <th></th>
                    <th>Squadra</th>
                    <th>P.ti</th>
                </tr>
                <?php foreach ($ranking as $key => $score) : ?>
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
