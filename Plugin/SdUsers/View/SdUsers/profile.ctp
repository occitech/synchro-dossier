<?php
	$this->start('navbar');
		echo $this->element('SynchroDossier.navbar');
	$this->end();
?>
<div class="users">
	<h2><?php echo $title_for_layout; ?></h2>

	<div class="row">
		<div class="span12">
			<h3><?= __d('sd_users', 'Personal Informations:') ?></h3>
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
			<?= $this->SdUsers->displayHeader($user); ?>
			<?= $this->SdUsers->displayLastName($user); ?>
			<?= $this->SdUsers->displayFirstName($user); ?>
			<?= $this->SdUsers->displaySociety($user); ?>
			<?= $this->SdUsers->displayMail($user); ?>
			<?= $this->SdUsers->displayLang($user); ?>
			<?= $this->SdUsers->displayFooter($user); ?>
			<?= $this->Form->end(array(
				'label' => __d('sd_users', 'Update user informations'),
				'class' => 'btn'
			)) ?>
		</div>
	</div>
	<?= $this->Form->create('User', array(
		'url' => array('plugin' => 'sd_users', 'controller' => 'sd_users', 'action' => 'changeUserPassword'),
		'div' => array('class' => 'row'),
		'inputDefaults' => array('div' => array('class' => 'span3'))
	)); ?>

		<legend><?= __d('sd_users', 'Change your password') ?></legend>
		<?= $this->Form->hidden('id', array('value' => $user['User']['id'])); ?>
		<?= $this->Form->input('oldPassword', array(
			'label' => __d('sd_users', 'Old password'),
			'type' => 'password',
			'required' => true
		)) ?>
		<?= $this->Form->input('newPassword', array(
			'label' => __d('sd_users', 'New password'),
			'type' => 'password',
			'required' => true
		)) ?>
		<?= $this->Form->input('confirmationPassword', array(
			'label' => __d('sd_users', 'Confirm new password'),
			'type' => 'password',
			'required' => true
		)) ?>
	<?= $this->Form->end(array('label' => __d('sd_users', 'Change your password'), 'class' => 'btn', 'div' => array('class' => 'span3'))) ?>

	<div class="row">
		<div class="span12">
		<h3><?= __d('sd_users', 'Your Folders') ?></h3>
		<?php if (!empty($folders)): ?>
			<table class="table table-stripped">
				<thead>
					<th><?= $this->Paginator->sort('filename', __d('sd_users', 'Dossier')); ?></th>
					<th><?= $this->Paginator->sort('created', __d('sd_users', 'Date')) ?></th>
					<th><?= $this->Paginator->sort('uploader_name', __d('sd_users', 'Creator')) ?></th>
					<th><?= __d('sd_users', 'Rights') ?></th>
					<th><?= __d('sd_users', 'Notifications') ?></th>
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

			<?php if ($this->elementExists('SynchroDossier.paging')): ?>
				<?= $this->element('SynchroDossier.paging') ?>

			<?php endif ?>
		<?php endif; ?>
		</div>
	</div>
</div>