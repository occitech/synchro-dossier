<div class="uploader">
	<?php echo $this->Form->create('UploadedFile'); ?>

	<?php echo $this->Form->input('UploadedFile.filename');?>
	
	<?php echo $this->Form->submit(__('Rename')); ?>
	<?php echo $this->Form->end(); ?>
</div>