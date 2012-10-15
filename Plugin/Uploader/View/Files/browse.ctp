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

	<table>
		<tr>
			<th><?php echo __('Fichier'); ?></th>
			<th><?php echo __('Auteur'); ?></th>
			<th><?php echo __('Date') ?></th>
			<th><?php echo __('Taille') ?></th>
			<th><?php echo __('Type') ?></th>
			<th><?php echo __('Actions') ?></th>
		</tr>
		<?php foreach ($files['ChildUploadedFile'] as $file): ?>
			<?php if (!$file['is_folder']): ?>
				<?php $lastVersion = $file['FileStorage'][sizeof($file['FileStorage']) - 1]; ?>
			<?php endif ?>
			<tr>
				<td>
					<?php if (!$file['is_folder']): ?>
						V<?php echo $file['current_version']; ?>
						<?php if ($file['current_version'] > 1): ?>
							<?php echo $this->Html->link('+', '#', array('class' => 'show-versions', 'id' => $file['id'])); ?>
						<?php endif; ?>
						<?php echo $this->Html->link(
							$file['filename'],
							array('controller' => 'files', 'action' => 'download', $lastVersion['id'])
						); ?>
					<?php else: ?>
						<?php echo $this->Html->link(
							$file['filename'],
							array('controller' => 'files', 'action' => 'browse', $file['id'])
						); ?>
					<?php endif; ?>
				</td>

				<td>
					<?php if (!$file['is_folder']): ?>
						<?php echo __('Par ') . $file['User']['name']; ?>
					<?php endif ?>
				</td>

				<td>
					<?php if (!$file['is_folder']): ?>
						<?php echo  $this->Time->format('j/m/Y H:i', $lastVersion['created']); ?>
					<?php endif ?>
				</td>

				<td>
					<?php if (!$file['is_folder']): ?>
						<?php echo $this->File->size($lastVersion['filesize']); ?>
					<?php endif ?>
				</td>

				<td>					
					<?php if (!$file['is_folder']): ?>
						<?php echo $this->File->mimeType($lastVersion['mime_type']); ?>
					<?php endif ?>
				</td>


				<td>
					<?php if ($file['is_folder']): ?>
						<?php if (isset($file['ChildUploadedFile'])): ?>
							<?php echo $this->Html->link(
								__('Télécharger'),
								array('controller' => 'files', 'action' => 'downloadZipFolder', $file['id'])
							); ?>
						<?php endif ?>
						<?php echo $this->Html->link(
							__('Renommer'),
							array('controller' => 'files', 'action' => 'rename', $file['parent_id'], $file['id'])
						); ?>
					<?php else: ?>
						<?php echo $this->Html->link(
							__('Ajouter une version'),
							array(
								'controller' => 'files',
								'action' => 'upload',
								$file['parent_id'],
								$file['filename']
							)
						); ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php if ($file['current_version'] > 1): ?>
					<?php $version = $file['current_version'] - 1; ?>
					<?php $fileVersions = array_reverse($file['FileStorage']); ?>
					<?php array_shift($fileVersions); ?>
					<?php foreach ($fileVersions as $k => $v): ?>
						<tr style="display:none;" class="versions-<?php echo $file['id'] ?> sub-version">	
							<td>
								V<?php echo $version--; ?>
								<?php echo $this->Html->link(
									$file['filename'],
									array(
										'controller' => 'files',
										'action' => 'download',
										$v['id']
									)
								); ?>
							</td>
							<td><?php echo __('Par ') . $file['User']['name']; ?></td>
							<td><?php echo  $this->Time->format('j/m/Y H:i', $v['created']); ?></td>
							<td><?php echo $this->File->size($v['filesize']); ?></td>
							<td></td>
							<td></td>
						</tr>			
					<?php endforeach ?>
			<?php endif ?>
		<?php endforeach; ?>
	</table>
</div>

