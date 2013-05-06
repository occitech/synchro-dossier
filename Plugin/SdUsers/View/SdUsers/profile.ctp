<div class="users">
	<h2><?php echo $title_for_layout; ?></h2>

	<h3><?= __('Personal Informations:') ?></h3>
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


	<h3><?= __d('sdusers', 'Your Folders') ?></h3>
	<?php if (!empty($folders)): ?>
		<table class="table table-stripped">
			<thead>
				<th><?= __d('uploader', 'Dossier'); ?></th>
				<th><?= __d('uploader', 'Date') ?></th>
				<th><?= __d('uploader', 'Type') ?></th>
				<th><?= __d('uploader', 'Actions') ?></th>
			</thead>
		<?php foreach ($folders as $folder): ?>
		<?php if ($this->UploaderAcl->userCan($folder['Aco'],'read')): ?>
				<td><?= $folder['UploadedFile']['filename']; ?></td>
				<td><?= $folder['UploadedFile']['created']; ?></td>
		<?php endif ?>
			<?php foreach ($folder['children'] as $child): ?>
				<?php if ($this->UploaderAcl->userCan($child['Aco'],'read')): ?>
					<td><?= $child['UploadedFile']['filename']; ?></td>
					<td><?= $child['UploadedFile']['created']; ?></td>
				<?php endif; ?>
			<?php endforeach ?>
		<?php endforeach ?>
		</table>
	<?php endif ?>
</div>