<div class="uploader">
	<?= $this->Form->create('UploadedFile'); ?>

	<?= $this->Form->input(
		'UploadedFile.filename',
		array('label' => __('Folder name'))
	);?>
	
	<?= $this->Form->input(
		'SdAlertEmail.subscribe',
		array(
			'label' => __('Inscription aux Alertes email'),
			'type' => 'checkbox'
		)
	); ?>

	<?= $this->Form->submit(__('Create')); ?>
	<?= $this->Form->end(); ?>
</div>