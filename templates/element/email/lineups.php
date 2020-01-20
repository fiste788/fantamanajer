<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Disposition $disposition
 * @var string $baseUrl
 * @var mixed $captains
 * @var string $caption
 * @var array $dispositions
 * @var mixed $full
 */
?>
<?php if (!empty($dispositions)): ?>
    <table width="100%">
        <caption><?= $caption ?></caption>
        <thead>
            <tr>
                <th><?= __('Name') ?></th>
                <th><?= __('Role') ?></th>
                <th><?= __('Club') ?></th>
                <?php if($dispositions[0]->member->ratings): ?>
                <th><abbr title="<?= __('Regular') ?>"><?= __('Reg') ?></abbr></th>
                <th><abbr title="<?= __('Yellow card') ?>"><?= __('YC') ?></abbr></th>
                <th><abbr title="<?= __('Red card') ?>"><?= __('RC') ?></abbr></th>
                <th><?= __('Assists') ?></th>
                <th><?= __('Goals') ?></th>
                <th><?= __('Points') ?></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dispositions as $key => $disposition): ?>
                <tr<?php if($disposition->position <= 11 && $disposition->consideration == 0) echo ' class="alert-danger"'; elseif($disposition->position > 11 && $disposition->consideration == 1) echo ' class="alert-success"' ?>>
                    <td>
                        <a style="text-decoration:none;color:#ff4081" href="<?php echo $baseUrl . '/players/' . $disposition->member->player_id ?>">
                            <?= $disposition->member->player->fullName ?>
                            <?php if($disposition->considerato == 2): ?>
                                <span id="cap">(C)</span>
                            <?php elseif(!$disposition->member->ratings && isset($captains) && array_search($disposition->member->id, $captains)): ?>
                                <span>(<?= array_search($disposition->member->id, $captains) ?>)</span>
                            <?php endif; ?>
                        </a>
                    </td>
                    <td><?php echo $disposition->member->role->abbreviation; ?></td>
                    <td>
                        <a style="text-decoration:none;color:#ff4081" href="<?= $baseUrl . '/clubs/' . $disposition->member->club->id ?>">
                            <?= $this->Html->image('Clubs/' . $disposition->member->club->id . '/photo/' . $disposition->member->club->id . '.png',['fullBase' => $full, 'height'=> 32, 'alt' => $disposition->member->club->abbreviation]) ?>
                        </a>
                    </td>
                    <?php if($disposition->member->ratings): ?>
                    <td><?php if ($disposition->member->ratings[0]->regular): ?>✓<?php endif; ?></td>
                    <td><?php if ($disposition->member->ratings[0]->yellow_card): ?>✓<?php endif; ?></td>
                    <td><?php if ($disposition->member->ratings[0]->red_card): ?>✓<?php endif; ?></td>
                    <td><?= ($disposition->member->ratings[0]->assist != 0) ? $disposition->member->ratings[0]->assist : ""; ?></td>
                    <td><?= ($disposition->member->ratings[0]->gol != 0) ? $disposition->member->ratings[0]->gol : ""; ?></td>
                    <td><?php if (!empty($disposition->member->ratings[0]->points)) echo ($disposition->consideration == 2) ? $disposition->member->ratings[0]->points * 2 : $disposition->member->ratings[0]->points; ?></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>