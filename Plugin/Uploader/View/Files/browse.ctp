<?= $this->Plupload->loadAsset('jquery'); ?>
<?php
	$this->start('navbar');
		echo $this->element('SynchroDossier.navbar');
	$this->end();
?>
<?php $this->start('sidebar'); ?>
	<?php if (isset($can)): ?>
		<?= $this->element('SynchroDossier.sidebar', array('can' => $can)); ?>
	<?php endif ?>
<?php $this->end();	?>
<?php if (!empty($folderTree)):?>
<ul class="breadcrumb">
	<?php
		$i = 0;
		$folderCount = count($folderTree);
	?>
	<?php foreach ($folderTree as $id => $folderName): ?>
		<?php $isLastElement = ++$i == $folderCount; ?>
		<li <?php if ($isLastElement) echo 'class="active"'; ?>>
			<?php
				if ($id === 0) {
					$url = '/';
				} else {
					$url = $this->Html->url(array('action' => 'browse', $id));
				}
			?>
			<?php if ($isLastElement): ?>
				<?= $folderName ?>
			<?php else: ?>
				<a href="<?= $url ?>"><?= $folderName; ?></a>
				<span class="divider">/</span>
			<?php endif ?>
		</li>
	<?php endforeach; ?>
</ul>
<?php endif ?>

<?php if (isset($folderId) && !is_null($folderId) && $this->UploaderAcl->userCan($folderAco['Aco'], 'create')): ?>
	<?= $this->element('Uploader.plupload_widget'); ?>
<?php endif ?>

<h2><?= $this->fetch('browse_title'); ?></h2>

<div class="uploader">
	<table class="table table-hover">
		<thead>
			<tr>
				<th><?= $this->Paginator->sort('filename', __d('uploader', 'Fichier')); ?></th>
				<th><?= $this->Paginator->sort('uploader_name', __d('uploader', 'Auteur')); ?></th>
				<th><?= $this->Paginator->sort('created', __d('uploader', 'Date')) ?></th>
				<th><?= $this->Paginator->sort('size', __d('uploader', 'Taille')) ?></th>
				<th><?= $this->Paginator->sort('mime_type', __d('uploader', 'Type')) ?></th>
				<th><?= __d('uploader', 'Actions') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($files as $file): ?>
				<?php if ($this->UploaderAcl->userCan($file['Aco'], 'read')): ?>
					<?php if (!$file['UploadedFile']['is_folder']): ?>
						<?php $lastVersion = $file['FileStorage'][sizeof($file['FileStorage']) - 1]; ?>
					<?php endif ?>
					<tr>
						<td>
							<?php if (!$file['UploadedFile']['is_folder']): ?>
								<span class="badge">V<?= $file['FileStorage'][0]['file_version']; ?></span>
								<?= $this->File->iconPreview($file['UploadedFile']); ?>
								<?php if (count($file['FileStorage']) > 1): ?>
									<?= $this->Html->link('<i class="icon-chevron-right"></i>',
										'#',
										array('class' => 'show-versions', 'id' => $file['UploadedFile']['id'], 'escape' => false)); ?>
								<?php endif; ?>
								<?= $this->Html->link(
									$file['UploadedFile']['filename'],
									array('controller' => 'files', 'action' => 'download', $lastVersion['id'])
								); ?>
							<?php else: ?>
								<?= $this->Html->link(
									$file['UploadedFile']['filename'],
									array('controller' => 'files', 'action' => 'browse', $file['UploadedFile']['id'])
								); ?>
							<?php endif; ?>
						</td>

						<td>
							<?php if (!$file['UploadedFile']['is_folder']): ?>
								<?= $file['UploadedFile']['uploader_name']; ?>
							<?php endif ?>
						</td>

						<td>
							<?php if (!$file['UploadedFile']['is_folder']): ?>
								<?=  $this->Time->format('j/m/Y H:i', $lastVersion['created']); ?>
							<?php endif ?>
						</td>

						<td>
							<?php if (!$file['UploadedFile']['is_folder']): ?>
								<?= $this->File->size($lastVersion['filesize'], 'o'); ?>
							<?php endif ?>
						</td>

						<td>
							<?php if (!$file['UploadedFile']['is_folder']): ?>
								<?= $this->File->mimeType($lastVersion['mime_type']); ?>
							<?php endif ?>
						</td>

						<td>
							<?php if ($file['UploadedFile']['is_folder']): ?>
								<?php if (!empty($file['ChildUploadedFile'])):?>
									<?php  if (empty($file['UploadedFile']['downloadable'])): ?>
									<?=
										$this->Html->tag('i', '', array(
											'rel' => 'tooltip',
											'class' => 'icon-download-alt not-allowed',
											'title' => __d('uploader', 'Folder size limit exceeded')
										))
									?>
									<?php else: ?>
										<?= $this->Html->link(
											__d('uploader', '<i class="icon-download-alt"></i>'),
											array('controller' => 'files', 'action' => 'downloadZipFolder', $file['UploadedFile']['id']),
											array(
												'rel' => 'tooltip',
												'title' => __d('uploader', 'Download folder as zipfile'),
												'escape' => false
											)
										); ?>
									<?php endif ?>
								<?php endif ?>
								<?= $this->Html->link(
									'<i class="icon-pencil"></i>',
									'#renameFolderModal',
									array(
										'rel' => 'tooltip',
										'title' => __d('uploader', 'Renommer le dossier'),
										'role' => 'button',
										'data-toggle' => 'modal',
										'class' => 'rename-folder',
										'data-id' => $file['UploadedFile']['id'],
										'data-filename' => $file['UploadedFile']['filename'],
										'escape' => false
									)
								); ?>
								<?php if (is_null($folderId) && $this->UploaderAcl->userCan($file['Aco'], 'change_right')): ?>
									<?= $this->Html->link(
										__d('uploader', '<i class="icon-user"></i>'),
										array('controller' => 'files', 'action' => 'rights', $file['UploadedFile']['id']),
										array(
											'rel' => 'tooltip',
											'title' => __d('uploader', 'Gérer les droits'),
											'escape' => false
										)
									); ?>
								<?php endif ?>
								<?php if (!$this->SynchroDossier->hasUserRole(CakeSession::read('Auth.User.role_id')) && $this->UploaderAcl->userCan($file['Aco'], 'delete')): ?>
									<?= $this->Html->link(
										__d('uploader', '<i class="icon-remove"></i>'),
										array('controller' => 'files', 'action' => 'deleteFolder', $file['UploadedFile']['id']),
										array(
											'rel' => 'tooltip',
											'title' => __d('uploader', 'Supprimer le dossier'),
											'escape' => false
										),
										__d('uploader', 'You are about to delete folder "%s". Are you sure ?', $file['UploadedFile']['filename'])
									); ?>
								<?php endif ?>
							<?php else: ?>
								<?= $this->Html->link(
									'<i class="icon-download-alt"></i>',
									array(
										'controller' => 'files',
										'action' => 'download',
										$lastVersion['id']
									),
									array(
										'rel' => 'tooltip',
										'title' => __d('uploader', 'Download'),
										'escape' => false
									)
								); ?>
								<?= $this->Html->link(
									'<i class="icon-plus-sign"></i>',
									'#addNewVersion',
									array(
										'action' => $this->Html->url(array(
											'plugin' => 'uploader',
											'controller' => 'files',
											'action' => 'upload',
											is_null($folderId) ? 'null' : $folderId,
											$this->File->customEncodeBase64($file['UploadedFile']['filename'])
										)),
										'rel' => 'tooltip',
										'title' => __d('uploader', 'New version'),
										'role' => 'button',
										'data-toggle' => 'modal',
										'data-filename' => $file['UploadedFile']['filename'],
										'class' => 'add-new-version',
										'escape' => false
									)
								); ?>
								<?= $this->Html->link(
									'<i class="icon-comment"></i>',
									'#formCommentModal',
									array(
										'ajax-url' => $this->Html->url(array(
											'controller' => 'comments',
											'action' => 'add',
											$file['UploadedFile']['id'],
										)),
										'rel' => 'tooltip',
										'role' => 'button',
										'data-toggle' => 'modal',
										'title' => __d('uploader', 'Commentaires'),
										'class' => 'comments',
										'escape' => false
									)
								); ?>
								<?= $this->Html->link(
									'<i class="icon-tags"></i>',
									'#formFileTagsModal',
									array(
										'ajax-url' => $this->Html->url(array(
											'controller' => 'files',
											'action' => 'addTags',
											$file['UploadedFile']['id'],
										)),
										'rel' => 'tooltip',
										'role' => 'button',
										'data-toggle' => 'modal',
										'title' => __d('uploader', 'File Tags'),
										'class' => 'comments',
										'escape' => false
									)
								); ?>
								<?php if ($this->UploaderAcl->userCan($file['Aco'], 'delete')): ?>
									<?= $this->Html->link(
										__d('uploader', '<i class="icon-remove"></i>'),
										array('controller' => 'files', 'action' => 'deleteFile', $file['UploadedFile']['id'], $lastVersion['id']),
										array(
											'rel' => 'tooltip',
											'title' => __d('uploader', 'Supprimer le fichier'),
											'escape' => false
										),
										__d('uploader', 'You are about to delete file "%s". Are you sure ?', $file['UploadedFile']['filename'])
									); ?>
								<?php endif ?>

							<?php endif; ?>
						</td>
					</tr>
					<?php if ($file['UploadedFile']['current_version'] > 1): ?>
						<?php $fileVersions = $file['FileStorage']; ?>
						<?php array_shift($fileVersions); ?>
						<?php foreach ($fileVersions as $fileVersion): ?>
							<tr style="display:none;" class="versions-<?= $file['UploadedFile']['id'] ?> sub-version">
								<td>
									V<?= $fileVersion['file_version']; ?>
									<?= $this->Html->link(
										$file['UploadedFile']['filename'],
										array(
											'controller' => 'files',
											'action' => 'download',
											$fileVersion['id']
										)
									); ?>
								</td>
								<td><?= $fileVersion['uploader_name']; ?></td>
								<td><?= $this->Time->format('j/m/Y H:i', $fileVersion['created']); ?></td>
								<td><?= $this->File->size($fileVersion['filesize'], 'o'); ?></td>
								<td><?= $this->File->mimeType($lastVersion['mime_type']); ?></td>
								<td>
									<?php if ($this->UploaderAcl->userCan($file['Aco'], 'delete')): ?>
										<?= $this->Html->link(
											__d('uploader', '<i class="icon-remove"></i>'),
											array('controller' => 'files', 'action' => 'deleteFile', $file['UploadedFile']['id'], $fileVersion['id']),
											array(
												'rel' => 'tooltip',
												'title' => __d('uploader', 'Supprimer le fichier'),
												'escape' => false
											),
											__d('uploader', 'You are about to delete file "%s". Are you sure ?', $file['UploadedFile']['filename'])
										); ?>
									<?php endif ?>
								</td>
							</tr>
						<?php endforeach ?>
					<?php endif ?>

				<?php endif ?>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php if ($this->elementExists('SynchroDossier.paging')): ?>
		<?= $this->element('SynchroDossier.paging', array('displayCounter' => false)) ?>
	<?php endif ?>
</div>
