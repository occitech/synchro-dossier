<div class="uploader">
	<?php echo $this->Form->create('UploadedFile'); ?>

	<?php echo $this->Form->input('UploadedFile.filename');?>
	
	<?php echo $this->Form->submit(__('Create')); ?>
	<?php echo $this->Form->end(); ?>
</div>