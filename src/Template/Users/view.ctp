<div class="users view large-10 medium-9 columns">
	<h2><?= h($user->fullName) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Name') ?></h6>
			<p><?= h($user->name) ?></p>
			<h6 class="subheader"><?= __('Surname') ?></h6>
			<p><?= h($user->surname) ?></p>
			<h6 class="subheader"><?= __('Email') ?></h6>
			<p><?= h($user->email) ?></p>
			<h6 class="subheader"><?= __('Username') ?></h6>
			<p><?= h($user->username) ?></p>
		</div>
	</div>
        <a href="<?= $this->Url->build(['action' => 'edit',$user->id]) ?>">Modifica</a>
</div>
<?php if (!empty($currentTeams)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Id') ?></th>
			<th><?= __('Name') ?></th>
			<th><?= __('User Id') ?></th>
			<th><?= __('Season Id') ?></th>
			<th><?= __('League Id') ?></th>
		</tr>
		<?php foreach ($user->teams as $teams): ?>
		<tr>
			<td><?= h($teams->id) ?></td>
			<td><?= h($teams->name) ?></td>
			<td><?= h($teams->user_id) ?></td>
			<td><?= h($teams->season_id) ?></td>
			<td><?= h($teams->league_id) ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
<?php if (!empty($user->teams)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Id') ?></th>
			<th><?= __('Name') ?></th>
			<th><?= __('User Id') ?></th>
			<th><?= __('Season Id') ?></th>
			<th><?= __('League Id') ?></th>
		</tr>
		<?php foreach ($user->teams as $teams): ?>
		<tr>
			<td><?= h($teams->id) ?></td>
			<td><?= h($teams->name) ?></td>
			<td><?= h($teams->user_id) ?></td>
			<td><?= h($teams->season_id) ?></td>
			<td><?= h($teams->league_id) ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>