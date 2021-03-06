<table class="table table-hover">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('filename', __d('uploader', 'Partages')); ?></th>
			<th><?= $this->Paginator->sort('uploader_name', __d('uploader', 'Ajouté par')); ?></th>
			<th><?= $this->Paginator->sort('created', __d('uploader', 'Dernière modification')) ?></th>
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
						<?=  $this->Time->format('j/m/Y H:i', $file['UploadedFile']['modified']); ?>
					</td>

					<td>
						<?= $this->File->size($file['UploadedFile']['size']); ?>
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
										'<i class="icon-download-alt"></i>',
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
									'<i class="icon-user"></i>',
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
							<?php endif; ?>
						<?php endif; ?>
					</td>
				</tr>
			<?php endif ?>
		<?php endforeach; ?>
	</tbody>
</table>