<div class="nodes promoted">
	<?php echo $this->Form->create('File', array('type' => 'file')); ?>

	<?php echo $this->Form->input('FileStorage.file', array('type' => 'file'));?>
	
	<?php echo $this->Form->submit('Upload'); ?>
	<?php echo $this->Form->end(); ?>
</div>