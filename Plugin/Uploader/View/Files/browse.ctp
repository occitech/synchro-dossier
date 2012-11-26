<?php //echo $this->Html->css('Uploader.style'); ?>
<?= $this->Html->script('Uploader.app'); ?>

<div class="uploader">
	<div class="actions">
		<?php if (!is_null($folderId)): ?>		
			<?= $this->Html->link(
				__('Uploader un fichier'),
				array('controller' => 'files', 'action' => 'upload', $folderId)
			); ?>
			 - 
			<?= $this->Html->link(
				__('Créer un sous dossier'),
				array('controller' => 'files', 'action' => 'createFolder', $folderId)
			); ?>
		<?php endif ?>
	</div>

	<div class="infos">
		<?= $this->Html->link('..', array($parentId)) ?>
	</div>
	<table class="table table-hover table-striped">
		<thead>
			<tr>
				<th><?= __('Fichier'); ?></th>
				<th><?= __('Auteur'); ?></th>
				<th><?= __('Date') ?></th>
				<th><?= __('Taille') ?></th>
				<th><?= __('Type') ?></th>
				<th><?= __('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($files as $file): ?>

				<?php if ($this->Acl->userCan($file['Aco'], 'read')): ?>
					<?php if (!$file['UploadedFile']['is_folder']): ?>
						<?php $lastVersion = $file['FileStorage'][sizeof($file['FileStorage']) - 1]; ?>
					<?php endif ?>
					<tr>
						<td>
							<?php if (!$file['UploadedFile']['is_folder']): ?>
								V<?= $file['UploadedFile']['current_version']; ?>
								<?php if ($file['UploadedFile']['current_version'] > 1): ?>
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
								<?= $file['User']['Profile']['name']; ?>
							<?php endif ?>
						</td>

						<td>
							<?php if (!$file['UploadedFile']['is_folder']): ?>
								<?=  $this->Time->format('j/m/Y H:i', $lastVersion['created']); ?>
							<?php endif ?>
						</td>

						<td>
							<?php if (!$file['UploadedFile']['is_folder']): ?>
								<?= $this->File->size($lastVersion['filesize']); ?>
							<?php endif ?>
						</td>

						<td>					
							<?php if (!$file['UploadedFile']['is_folder']): ?>
								<?= $this->File->mimeType($lastVersion['mime_type']); ?>
							<?php endif ?>
						</td>


						<td>
							<?php if ($file['UploadedFile']['is_folder']): ?>
								<?php if (!empty($file['ChildUploadedFile'])): ?>
									<?= $this->Html->link(
										__('Télécharger'),
										array('controller' => 'files', 'action' => 'downloadZipFolder', $file['UploadedFile']['id'])
									); ?>
								<?php endif ?>
								<?= $this->Html->link(
									__('Renommer'),
									array('controller' => 'files', 'action' => 'rename', $file['UploadedFile']['parent_id'], $file['UploadedFile']['id'])
								); ?>
								<?php if (is_null($folderId) && $this->Acl->userCan($file['Aco'], 'change_right')): ?>
									<?= $this->Html->link(
										__('Gérer les droits'),
										array('controller' => 'files', 'action' => 'rights', $file['UploadedFile']['id'])
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
										'title' => __('Download'),
										'escape' => false
									)
								); ?>
								<?= $this->Html->link(
									'<i class="icon-plus-sign"></i>',
									array(
										'controller' => 'files',
										'action' => 'upload',
										$file['UploadedFile']['parent_id'],
										$file['UploadedFile']['filename']
									),
									array(
										'rel' => 'tooltip',
										'title' => __('New version'),
										'escape' => false
									)
								); ?>
								<?= $this->Html->link(
									'<i class="icon-comment"></i>',
									array(
										'controller' => 'comments',
										'action' => 'add',
										$file['UploadedFile']['id'],
									),
									array(
										'rel' => 'tooltip',
										'title' => __('Comment'),
										'escape' => false
									)
								); ?>

							<?php endif; ?>
						</td>
					</tr>
					<?php if ($file['UploadedFile']['current_version'] > 1): ?>
							<?php $version = $file['UploadedFile']['current_version'] - 1; ?>
							<?php $fileVersions = array_reverse($file['FileStorage']); ?>
							<?php array_shift($fileVersions); ?>
							<?php foreach ($fileVersions as $fileVersion): ?>
								<tr style="display:none;" class="versions-<?= $file['UploadedFile']['id'] ?> sub-version">	
									<td>
										V<?= $version--; ?>
										<?= $this->Html->link(
											$file['UploadedFile']['filename'],
											array(
												'controller' => 'files',
												'action' => 'download',
												$fileVersion['id']
											)
										); ?>
									</td>
									<td><?= $file['User']['Profile']['name']; ?></td>
									<td><?= $this->Time->format('j/m/Y H:i', $fileVersion['created']); ?></td>
									<td><?= $this->File->size($fileVersion['filesize']); ?></td>
									<td></td>
									<td></td>
								</tr>			
							<?php endforeach ?>
					<?php endif ?>

				<?php endif ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

