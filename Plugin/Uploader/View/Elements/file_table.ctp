<table class="table table-hover">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('filename', __d('uploader', 'Nom')); ?></th>
			<th><?= $this->Paginator->sort('uploader_name', __d('uploader', 'Ajouté par')); ?></th>
			<th><?= $this->Paginator->sort('created', __d('uploader', 'Dernière modification')) ?></th>
			<th><?= $this->Paginator->sort('size', __d('uploader', 'Taille')) ?></th>
			<th><?= $this->Paginator->sort('mime_type', __d('uploader', 'Type')) ?></th>
			<?php if (!isset($isFind)): ?>
				<th><?= __d('uploader', 'Actions') ?></th>
			<?php endif; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($files as $file): ?>
			<?php if ($this->UploaderAcl->userCan($file['Aco'], 'read')): ?>
				<?php if (!$file['UploadedFile']['is_folder']): ?>
					<?php $lastVersion = $file['FileStorage'][0]; ?>
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
						<?= $file['UploadedFile']['uploader_name']; ?>
					</td>

					<td>
						<?php if (!$file['UploadedFile']['is_folder']): ?>
							<?=  $this->Time->format('j/m/Y H:i', $lastVersion['modified']); ?>
						<?php else: ?>
							<?=  $this->Time->format('j/m/Y H:i', $file['UploadedFile']['modified']); ?>
						<?php endif; ?>
					</td>

					<td>
						<?php if (!$file['UploadedFile']['is_folder']): ?>
							<?= $this->File->size($lastVersion['filesize']); ?>
						<?php else: ?>
							<?= $this->File->size($file['UploadedFile']['size']); ?>
						<?php endif; ?>
					</td>

					<td>
						<?php if (!$file['UploadedFile']['is_folder']): ?>
							<?= $this->File->mimeType($lastVersion['mime_type']); ?>
						<?php endif ?>
					</td>

					<?php if (!isset($isFind)): ?>
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
											'data-event' => 'ga',
											'data-category' => 'Télécharger',
											'data-action' => 'click',
											'rel' => 'tooltip',
											'title' => __d('uploader', 'Download folder as zipfile'),
											'escape' => false
										)
									); ?>
								<?php endif ?>
							<?php endif ?>
							<?php
							// Allow root sharing rename and owner renaming ...
							if($this->UploaderAcl->userCan($file['Aco'], 'update') ||
								CakeSession::read('Auth.User.user_id') == $file['UploadedFile']['user_id']
							): ?>
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
							<?php endif;?>
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
									'<i class="icon-trash"></i>',
									array('controller' => 'files', 'action' => 'deleteFolder', $file['UploadedFile']['id']),
									array(
										'rel' => 'tooltip',
										'title' => __d('uploader', 'Supprimer le dossier'),
										'escape' => false,
										'data-event' => 'ga',
										'data-category' => 'Supprimer',
										'data-action' => 'click',
									),
									__d('uploader', 'You are about to delete folder "%s". Are you sure ?', $file['UploadedFile']['filename'])
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
									),
									array(
										'data-event' => 'ga',
										'data-category' => 'Télécharger',
										'data-action' => 'click'
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
										'<i class="icon-trash"></i>',
										array('controller' => 'files', 'action' => 'deleteFile', $file['UploadedFile']['id'], $fileVersion['id']),
										array(
											'rel' => 'tooltip',
											'title' => __d('uploader', 'Supprimer le fichier'),
											'escape' => false,
											'data-event' => 'ga',
											'data-category' => 'Supprimer',
											'data-action' => 'click',
										),
										__d('uploader', 'You are about to delete file "%s". Are you sure ?', $file['UploadedFile']['filename'])
									); ?>
								<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php if(isset($isFind) && $isFind && empty($files)): ?>
	<?= __d('uploader', 'Pas de résultats pour cette recherche'); ?>
<?php endif; ?>