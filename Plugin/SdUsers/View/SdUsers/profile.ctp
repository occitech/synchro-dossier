<div class="users">
	<h2><?php echo $title_for_layout; ?></h2>

	<div class="row">
		<div class="span12">
			<h3><?= __d('sdusers', 'Personal Informations:') ?></h3>
				<?php if (!$isAdmin): ?>
				<dl class="users-informations--list">
					<dt><?= __d('sdusers', 'Name:') ?></dt>
					<dd><?= $user['Profile']['name'] ?></dd>
					<dt><?= __d('sdusers', 'Firstname:') ?></dt>
					<dd><?= $user['Profile']['firstname'] ?></dd>
					<dt><?= __d('sdusers', 'Society:') ?></dt>
					<dd><?= $user['Profile']['society'] ?></dd>
					<dt><?= __d('sdusers', 'Email:') ?></dt>
					<dd><?= $user['User']['email'] ?></dd>
				</dl>
				<?php else: ?>
					<?= $this->Form->create('User'); ?>
						<?= $this->Form->hidden('id', array('value' => $user['User']['id'])) ?>
						<?= $this->Form->input('firstname', array('label' => __d('sdusers', 'Firstname'), 'type' => 'text')); ?>
						<?= $this->Form->input('lastname', array('label' => __d('sdusers', 'Lastname'), 'type' => 'text')); ?>
						<?= $this->Form->input('society', array('label' => __d('sdusers', 'Society'), 'type' => 'text')); ?>
						<?= $this->Form->input('email', array('label' => __d('sdusers', 'Email'), 'type' => 'email')); ?>
					<?= $this->Form->end(__d('sdusers', 'Modifier les informations')) ?>
				<?php endif; ?>
		</div>
	</div>
	<?= $this->Form->create('User', array(
		'url' => array('plugin' => 'sd_users', 'controller' => 'sd_users', 'action' => 'changeUserPassword'),
		'div' => array('class' => 'row'),
		'inputDefaults' => array('div' => array('class' => 'span3'))
	)); ?>

		<legend><?= __d('sdusers', 'Change your password') ?></legend>
		<?= $this->Form->hidden('id', array('value' => $user['User']['id'])); ?>
		<?= $this->Form->input('oldPassword', array(
			'label' => __d('sdusers', 'Old password'),
			'type' => 'password',
			'required' => true
		)) ?>
		<?= $this->Form->input('newPassword', array(
			'label' => __d('sdusers', 'New password'),
			'type' => 'password',
			'required' => true
		)) ?>
		<?= $this->Form->input('confirmationPassword', array(
			'label' => __d('sdusers', 'Confirm new password'),
			'type' => 'password',
			'required' => true
		)) ?>
	<?= $this->Form->end(array('label' => __d('sdusers', 'Change your password'), 'class' => 'btn', 'div' => array('class' => 'span3'))) ?>

	<div class="row">
		<div class="span12">
		<h3><?= __d('sdusers', 'Your Folders') ?></h3>
		<?php if (!empty($folders)): ?>
			<table class="table table-stripped">
				<thead>
					<th><?= __d('sdusers', 'Dossier'); ?></th>
					<th><?= __d('sdusers', 'Date') ?></th>
					<th><?= __d('sdusers', 'Creator') ?></th>
					<th><?= __d('sdusers', 'Rights') ?></th>
					<th><?= __d('sdusers', 'Notifications') ?></th>
				</thead>
				<tbody>
			<?php foreach ($folders as $folder): ?>
			<?php if ($this->UploaderAcl->userCan($folder['Aco'],'read')): ?>
					<tr>
						<td><?= $folder['UploadedFile']['filename']; ?></td>
						<td><?= $folder['UploadedFile']['created']; ?></td>
						<td><?= $folder['UploadedFile']['uploader_name']; ?></td>
						<td><?= $this->SynchroDossier->displayRights($folder['Aco']['Aro'])?></td>
						<td><?php echo $this->SynchroDossier->displaySubscriptionInput($folder, $user['User']['id'], $emailsAlerts);?></td>
					</tr>
			<?php endif ?>
			<?php endforeach ?>
				</tbody>
			</table>
		<?php endif ?>
		</div>
	</div>
</div>