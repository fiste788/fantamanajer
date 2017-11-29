<?php if (!empty($dispositions)): ?>
    <table width="100%">
        <caption><?= $caption ?></caption>
        <thead>
            <tr>
                <th><?= __('Nome') ?></th>
                <th><?= __('Ruolo') ?></th>
                <th><?= __('Club') ?></th>
                <th><abbr title="<?= __('Titolare') ?>"><?= __('Tit') ?></abbr></th>
                <th><abbr title="<?= __('Ammonito') ?>"><?= __('Amm') ?></abbr></th>
                <th><abbr title="<?= __('Espulso') ?>"><?= __('Esp') ?></abbr></th>
                <th><?= __('Assist') ?></th>
                <th><?= __('Gol') ?></th>
                <th><?= __('Punti') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dispositions as $key => $disposition): ?>
                <tr<?php if($disposition->position <= 11 && $disposition->consideration == 0) echo ' class="alert-danger"'; elseif($disposition->position > 11 && $disposition->consideration == 1) echo ' class="alert-success"' ?>>
                    <td>
                        <a style="text-decoration:none;color:#ff4081" href="<?php echo $baseUrl . '/players/' . $disposition->member->player_id ?>"><?= ($disposition->considerato == 2) ? $disposition->member->player->fullName . '<span id="cap">(C)</span>' : $disposition->member->player->fullName; ?></a>
                    </td>
                    <td><?php echo $disposition->member->role->abbreviation; ?></td>
                    <td>
                        <a style="text-decoration:none;color:#ff4081" href="<?= $baseUrl . '/clubs/' . $disposition->member->club->id ?>">
                            <?= $this->Html->image('Clubs/' . $disposition->member->club->id . '/photo/' . $disposition->member->club->id . '.png',['fullBase' => $full, 'height'=> 32, 'alt' => $disposition->member->club->abbreviation]) ?>
                        </a>
                    </td>
                    <td><?php if ($disposition->member->ratings[0]->regular): ?>✓<?php endif; ?></td>
                    <td><?php if ($disposition->member->ratings[0]->yellow_card): ?>✓<?php endif; ?></td>
                    <td><?php if ($disposition->member->ratings[0]->red_card): ?>✓<?php endif; ?></td>
                    <td><?= ($disposition->member->ratings[0]->assist != 0) ? $disposition->member->ratings[0]->assist : ""; ?></td>
                    <td><?= ($disposition->member->ratings[0]->gol != 0) ? $disposition->member->ratings[0]->gol : ""; ?></td>
                    <td><?php if (!empty($disposition->member->ratings[0]->points)) echo ($disposition->member->ratings[0]->consideration == 2) ? $disposition->member->ratings[0]->points * 2 : $disposition->member->ratings[0]->points; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>