<div class="uploader">
	<?php echo $this->Form->create('UploadedFile'); ?>

	<?php echo $this->Form->hidden('UploadedFile.id');?>
	<?php echo $this->Form->input('UploadedFile.filename');?>

	<?php echo $this->Form->submit(__d('uploader', 'Rename')); ?>
	<?php echo $this->Form->end(); ?>
</div>