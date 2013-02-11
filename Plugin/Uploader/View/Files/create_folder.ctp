<div class="uploader">
	<?php echo $this->Form->create('UploadedFile'); ?>

	<?php echo $this->Form->input('UploadedFile.filename', array('label' => __d('uploader', 'Folder name')));?>

	<?php echo $this->Form->submit(__d('uploader', 'Create')); ?>
	<?php echo $this->Form->end(); ?>
</div>