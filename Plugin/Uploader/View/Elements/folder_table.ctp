<table class="table table-hover">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('filename', __d('uploader', 'Partages')); ?></th>
			<th><?= $this->Paginator->sort('uploader_name', __d('uploader', 'Auteur')); ?></th>
			<th><?= $this->Paginator->sort('created', __d('uploader', 'Date')) ?></th>
			<th><?= $this->Paginator->sort('size', __d('uploader', 'Taille')) ?></th>
			<th><?= __d('uploader', 'Actions') ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($files as $file): ?>
			<?php if ($this->UploaderAcl->userCan($file['Aco'], 'read')): ?>
				<tr>
					<td>
						<?= $this->Html->link(
							$file['UploadedFile']['filename'],
							array('controller' => 'files', 'action' => 'browse', $file['UploadedFile']['id'])
						); ?>
					</td>

					<td>
						<?= $file['UploadedFile']['uploader_name']; ?>
					</td>

					<td>
						<?=  $this->Time->format('j/m/Y H:i', $file['UploadedFile']['created']); ?>
					</td>

					<td>
						<?php if (!$file['UploadedFile']['is_folder']): ?>
							<?= $this->File->size($lastVersion['filesize'], 'o'); ?>
						<?php endif ?>
					</td>

					<td>
						<?php if (!isset($isFind)): ?>
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
								<?php if ($this->UploaderAcl->userCan($file['Aco'], 'change_right')): ?>
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
											'escape' => false,
											'data-event' => 'ga',
											'data-category' => 'Supprimer',
											'data-action' => 'click',
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
										'escape' => false,
										'data-event' => 'ga',
										'data-category' => 'Télécharger',
										'data-action' => 'click'
									)
								); ?>
								<?= $this->Html->link(
									'<i class="icon-pencil"></i>',
									'#renameFileModal',
									array(
										'rel' => 'tooltip',
										'title' => __d('uploader', 'Renommer le fichier'),
										'role' => 'button',
										'data-toggle' => 'modal',
										'class' => 'rename-file',
										'data-id' => $file['UploadedFile']['id'],
										'data-filename' => $file['UploadedFile']['filename'],
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
								<?php
									$commentsIconClass = 'icon-comment';
									if (empty($file['Comment'])) {
										$commentsIconClass .= ' icon-white';
										$commentsTitle = __d('uploader', 'Ajouter un commentaire');
									} else {
										$commentsTitle = __d(
											'uploader',
											'Dernier commentaire le %s à %s par %s',
											date('d/m/Y', strtotime($file['Comment'][0]['created'])),
											date('H:i', strtotime($file['Comment'][0]['created'])),
											$file['Comment'][0]['name']
										);
									}

									echo $this->Html->link(
										'<i class="'. $commentsIconClass . '"></i>',
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
											'title' => $commentsTitle,
											'class' => 'comments',
											'escape' => false
										)
									);
								?>
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
										'title' => __d('uploader', 'File Tags') . (!empty($file['FileTag']) ? sprintf(' : %s', implode(', ', Hash::extract($file['FileTag'], '{n}.Term.title'))) : null),
										'class' => 'comments',
										'escape' => false,
										'data-event' => 'ga',
										'data-category' => 'Mots-clefs',
										'data-action' => 'click',
									)
								); ?>
								<?php if ($this->UploaderAcl->userCan($file['Aco'], 'delete')): ?>
									<?= $this->Html->link(
										__d('uploader', '<i class="icon-remove"></i>'),
										array('controller' => 'files', 'action' => 'deleteFile', $file['UploadedFile']['id'], $lastVersion['id']),
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
								<?php endif ?>

							<?php endif; ?>
						<?php endif; ?>
					</td>
				</tr>
			<?php endif ?>
		<?php endforeach; ?>
	</tbody>
</table>