<div class="uploader">
	<?php echo $this->Form->create('UploadedFile'); ?>

	<?= $this->Form->input(
		'UploadedFile.filename',
		array('label' => __('Folder name'))
	);?>
	
	<?php echo $this->Form->submit(__('Create')); ?>
	<?php echo $this->Form->end(); ?>
</div>