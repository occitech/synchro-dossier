<?php echo $this->Html->css('Uploader.style'); ?>
<?php echo $this->Html->script('Uploader.app'); ?>
<div class="uploader">
	<div class="actions">
		<?php echo $this->Html->link(
			__('Uploader un fichier'),
			array('controller' => 'files', 'action' => 'upload', $files['UploadedFile']['id'])
		); ?>
		 - 
		<?php echo $this->Html->link(
			__('Créer un sous dossier'),
			array('controller' => 'files', 'action' => 'createFolder', $files['UploadedFile']['id'])
		); ?>
	</div>

	<?php if ($files['ParentUploadedFile']['id'] != null): ?>
		<div class="infos">
			<?php echo $this->Html->link('..', array($files['ParentUploadedFile']['id'])) ?>
		</div>		
	<?php endif ?>

	<?php foreach ($files['ChildUploadedFile'] as $file): ?>
		<div class="infos">
			<?php if ($file['is_folder']): ?>
				<div>
					<span class="file-actions">
						<span class="info">
							<?php echo $this->Html->link(
								__('Renommer le dossier'),
								array('controller' => 'files', 'action' => 'rename', $file['parent_id'], $file['id'])
							); ?>
						</span>

						<?php if (isset($file['ChildUploadedFile'])): ?>
						<span class="info">
							<?php echo $this->Html->link(
								__('Télécharger le dossier'),
								array('controller' => 'files', 'action' => 'downloadZipFolder', $file['id'])
							); ?>
						</span>
						<?php endif ?>
					</span>
				</div>
				<?php echo $this->Html->link(
					$file['filename'],
					array('controller' => 'files', 'action' => 'browse', $file['id'])
				); ?>

			<?php else: ?>
				<?php $lastVersion = $file['FileStorage'][sizeof($file['FileStorage']) - 1]; ?>
				<div>
					V<?php echo $file['current_version']; ?>
					<?php if ($file['current_version'] > 1): ?>
						<?php echo $this->Html->link('+', '#', array('class' => 'show-versions')); ?>
					<?php endif; ?>
					<?php echo $this->Html->link(
						$file['filename'],
						array('controller' => 'files', 'action' => 'download', $lastVersion['id'])
					); ?>
					<span class="file-actions">
						<span class="info">
							<?php echo __('Par ') . $file['User']['name']; ?>
						</span>
						<span class="info">
							<?php echo  $this->Time->format('j/m/Y H:i', $lastVersion['created']); ?>
						</span>
						<span class="info">
							<?php 
							$size = $lastVersion['filesize']; ?>
							<?php echo $this->File->size($size); ?>
						</span>
						<span class="info">
							<?php echo $this->File->mimeType($file['mime_type']); ?>
						</span>
						<span class="info">
							<?php echo $this->Html->link(
								__('Ajouter une version'),
								array(
									'controller' => 'files',
									'action' => 'upload',
									$file['parent_id'],
									$file['filename']
								)
							); ?>
						</span>
					</span>
				</div>
				
				<?php if ($file['current_version'] > 1): ?>
					<div class="versions" style="display: none;">
						<?php $version = $file['current_version'] - 1; ?>
						<?php $fileVersions = array_reverse($file['FileStorage']); ?>
						<?php array_shift($fileVersions); ?>
						<?php foreach ($fileVersions as $k => $v): ?>					
							<div class="version">
								V<?php echo $version--; ?>
								<span>
									<?php echo $this->Html->link(
										$file['filename'],
										array(
											'controller' => 'files',
											'action' => 'download',
											$v['id']
										)
									); ?>
								</span>
							</div>
						<?php endforeach ?>
					</div>
				<?php endif ?>
			<?php endif ?>
		</div>
	<?php endforeach ?>
</div>