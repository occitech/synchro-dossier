<?php
	$this->start('navbar');
		echo $this->element('SynchroDossier.navbar');
	$this->end();
?>
<?php if(Configure::check('sd.tooltip.rights')) {
	$rightHelp = '<i class="icon-info-sign"
			data-toggle="popover"
			data-title=""
			data-trigger="' . Configure::read('sd.tooltip.rights.trigger') . '"
			data-original-title="' . Configure::read('sd.tooltip.rights.title') . '"
			data-content="' . Configure::read('sd.tooltip.rights.text') . '"
			data-placement="' . Configure::read('sd.tooltip.rights.position') . '"
	></i>';
} else {
	$rightHelp = '';
}
if (Configure::check('sd.tooltip.email_alert')) {
	$alertHelp = '<i class="icon-info-sign"
			data-toggle="popover"
			data-title=""
			data-trigger="' . Configure::read('sd.tooltip.email_alert.trigger') . '"
			data-original-title="' . Configure::read('sd.tooltip.email_alert.title') . '"
			data-content="' . Configure::read('sd.tooltip.email_alert.text') . '"
			data-placement="' . Configure::read('sd.tooltip.email_alert.position') . '"
	></i>';
} else {
	$alertHelp = '';
}
?>
<div class="users">

	<h3><?= __d('sd_users', 'Your Folders') ?></h3>
	<?php if (!empty($folders)): ?>
		<table class="table table-stripped">
			<thead>
				<th><?= $this->Paginator->sort('filename', __d('sd_users', 'Dossier')); ?></th>
				<th><?= $this->Paginator->sort('created', __d('sd_users', 'Date')) ?></th>
				<th><?= $this->Paginator->sort('uploader_name', __d('sd_users', 'Creator')) ?></th>
				<th><?= __d('sd_users', 'Rights') . ' ' . $rightHelp?> </th>
				<th><?= __d('sd_users', 'Notifications') . ' ' . $alertHelp?></th>
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
