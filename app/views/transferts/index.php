<?php if ($this->transferts != FALSE): ?>
    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th>N.</th>
                    <th>Giocatore nuovo</th>
                    <th>Giocatore vecchio</th>
                    <th>Giornata</th>
                    <th>Obbligato</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->transferts as $key => $transferts): ?>
                    <tr>
                        <td><?php echo $key + 1; ?></td>
                        <td><a href="<?php echo $this->router->generate('members_show', array('id' => $transferts->new_member_id)); ?>"><?php echo $val->getNewMember()->player; ?></a></td>
                        <td><a href="<?php echo $this->router->generate('members_show', array('id' => $transferts->old_member_id)); ?>"><?php echo $val->getOldMember()->player; ?></a></td>
                        <td><?php echo $transferts->matchday_id; ?></td>
                        <td><?php if ($transferts->isConstrainded()): ?><i class="icon-ok"></i><?php endif; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>Non ha effettuato alcun trasferimento</p>
<?php endif; ?>
