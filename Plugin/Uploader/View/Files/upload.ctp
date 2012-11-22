<div class="nodes promoted">
	<?= $this->Form->create('File', array('type' => 'file')); ?>

	<?= $this->Form->hidden('UploadedFile.id', array('value' =>$folderId)); // For AclRowLevel ?>

	<?= $this->Form->input('FileStorage.file', array('type' => 'file'));?>
	
	<?= $this->Form->submit('Upload'); ?>
	<?= $this->Form->end(); ?>
</div>