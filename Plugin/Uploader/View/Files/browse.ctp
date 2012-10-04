<?php echo $this->Html->css('Uploader.style'); ?>
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
				<?php echo $this->Html->link(
					$file['filename'],
					array('controller' => 'files', 'action' => 'view', $file['id'])
				); ?>
				<span class="uploader-version">
					nombres versions : <?php echo $file['current_version'] ?>
				</span>
			<?php endif ?>
		</div>
	<?php endforeach ?>
</div>