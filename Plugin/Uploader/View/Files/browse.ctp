<?php echo $this->Html->css('Uploader.style'); ?>
<?php echo $this->Html->script('Uploader.app'); ?>
<div class="uploader">
	<div class="uploader-actions">
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
		<div class="uploader-infos">
			<?php echo $this->Html->link('..', array($files['ParentUploadedFile']['id'])) ?>
		</div>		
	<?php endif ?>

	<?php foreach ($files['ChildUploadedFile'] as $file): ?>
		<div class="uploader-infos">
			<?php if ($file['is_folder']): ?>
				<?php echo $this->Html->link(
					$file['filename'],
					array('controller' => 'files', 'action' => 'browse', $file['id'])
				); ?>		
			<?php else: ?>
				<div>
					V<?php echo $file['current_version']; ?>
					<?php if ($file['current_version'] > 1): ?>
						<?php echo $this->Html->link('+', '#', array('class' => 'show-versions')); ?>
					<?php endif; ?>
					<?php echo $this->Html->link(
						$file['filename'],
						array('controller' => 'files', 'action' => 'view', $file['id'])
					); ?>
					<span>
						nombres versions : <?php echo $file['current_version'] ?>
					</span>
					<span>
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
				</div>
				
				<?php if ($file['current_version'] > 1): ?>
					<div class="uploader-versions" style="display: none;">
					<?php $version = $file['current_version']; ?>
					<?php $fileVersions = array_reverse($file['FileStorage']); ?>
						<?php foreach ($fileVersions as $k => $v): ?>					
							<div class="uploader-version">
								V<?php echo $version--; ?>
								<span>
									<?php echo $file['filename']; ?>
								</span>
							</div>
						<?php endforeach ?>
					</div>
				<?php endif ?>
			<?php endif ?>
		</div>
	<?php endforeach ?>
</div>