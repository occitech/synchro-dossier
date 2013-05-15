<?php
	$this->start('navbar');
		echo $this->element('SynchroDossier.navbar');
	$this->end();
?>
<div class="users">
	<h2><?php echo $title_for_layout; ?></h2>

	<div class="row">
		<div class="span12">
			<h3><?= __d('SdUsers', 'Personal Informations:') ?></h3>
				<?php if (!$isAdmin): ?>
				<dl class="users-informations--list">
					<dt><?= __d('SdUsers', 'Name:') ?></dt>
					<dd><?= $user['Profile']['name'] ?></dd>
					<dt><?= __d('SdUsers', 'Firstname:') ?></dt>
					<dd><?= $user['Profile']['firstname'] ?></dd>
					<dt><?= __d('SdUsers', 'Society:') ?></dt>
					<dd><?= $user['Profile']['society'] ?></dd>
					<dt><?= __d('SdUsers', 'Email:') ?></dt>
					<dd><?= $user['User']['email'] ?></dd>
				</dl>
				<?php else: ?>
					<?= $this->Form->create('User', array(
						'url' => array(
							'plugin' => 'sd_users',
							'controller' => 'sd_users',
							'action' => 'profile',
							$user['User']['id']
						),
						'type' => 'file'
					)); ?>
						<?= $this->Form->hidden('User.id', array('value' => $user['User']['id'])) ?>
						<?= $this->Form->hidden('Profile.user_id', array('value' => $user['User']['id'])) ?>
						<?= $this->Form->hidden('Profile.id', array('value' => $user['Profile']['id'])) ?>
						<?= $this->Form->input('Profile.firstname', array(
							'label' => __d('SdUsers', 'Firstname'),
							'type' => 'text',
							'value' => $user['Profile']['firstname']
						)); ?>
						<?= $this->Form->input('Profile.name', array(
							'label' => __d('SdUsers', 'Lastname'),
							'type' => 'text',
							'value' => $user['Profile']['name']
						)); ?>
						<?= $this->Form->input('Profile.society', array(
							'label' => __d('SdUsers', 'Society'),
							'type' => 'text',
							'value' => $user['Profile']['society']
						)); ?>
						<?= $this->Form->input('User.email', array(
							'label' => __d('SdUsers', 'Email'),
							'type' => 'email',
							'value' => $user['User']['email']
						)); ?>
						<?= $this->Form->input('Profile.language_id', array(
							'label' => __d('SdUsers', 'Language'),
							'type' => 'select',
							'selected' => $user['Profile']['language_id'],
						)); ?>
					<?= $this->Form->end(array(
						'label' => __d('SdUsers', 'Update user informations'),
						'class' => 'btn'
					)) ?>
				<?php endif; ?>
		</div>
	</div>
	<?= $this->Form->create('User', array(
		'url' => array('plugin' => 'sd_users', 'controller' => 'sd_users', 'action' => 'changeUserPassword'),
		'div' => array('class' => 'row'),
		'inputDefaults' => array('div' => array('class' => 'span3'))
	)); ?>

		<legend><?= __d('SdUsers', 'Change your password') ?></legend>
		<?= $this->Form->hidden('id', array('value' => $user['User']['id'])); ?>
		<?= $this->Form->input('oldPassword', array(
			'label' => __d('SdUsers', 'Old password'),
			'type' => 'password',
			'required' => true
		)) ?>
		<?= $this->Form->input('newPassword', array(
			'label' => __d('SdUsers', 'New password'),
			'type' => 'password',
			'required' => true
		)) ?>
		<?= $this->Form->input('confirmationPassword', array(
			'label' => __d('SdUsers', 'Confirm new password'),
			'type' => 'password',
			'required' => true
		)) ?>
	<?= $this->Form->end(array('label' => __d('SdUsers', 'Change your password'), 'class' => 'btn', 'div' => array('class' => 'span3'))) ?>

	<div class="row">
		<div class="span12">
		<h3><?= __d('SdUsers', 'Your Folders') ?></h3>
		<?php if (!empty($folders)): ?>
			<table class="table table-stripped">
				<thead>
					<th><?= __d('SdUsers', 'Dossier'); ?></th>
					<th><?= __d('SdUsers', 'Date') ?></th>
					<th><?= __d('SdUsers', 'Creator') ?></th>
					<th><?= __d('SdUsers', 'Rights') ?></th>
					<th><?= __d('SdUsers', 'Notifications') ?></th>
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
				<?php endif; ?>
			<?php endforeach ?>
				</tbody>
			</table>
		<?php endif; ?>
		</div>
	</div>
</div>