<div class="uploader">
	<?= $this->Form->create('UploadedFile'); ?>

	<?= $this->Form->input(
		'UploadedFile.filename',
		array('label' => __d('uploader', 'Folder name'))
	);?>

	<?= $this->Form->input(
		'SdAlertEmail.subscribe',
		array(
			'label' => __d('uploader', 'Inscription aux Alertes email'),
			'type' => 'checkbox'
		)
	); ?>

	<?= $this->Form->submit(__d('uploader', 'Create')); ?>
	<?= $this->Form->end(); ?>
</div>