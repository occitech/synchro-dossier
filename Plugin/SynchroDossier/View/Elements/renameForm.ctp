<div class="modal-body no-overflow-y">
	<div class="uploader">
		<?= $this->Form->create('UploadedFile',	array(
			'type' => 'put',
			'url' => array(
				'plugin' => 'uploader',
				'controller' => 'files',
				'action' => 'rename',
				$folderId
			)
		)); ?>

		<?= $this->Form->hidden('id'); ?>
		<?= $this->Form->input('UploadedFile.filename', array(
			'label' => false,
			'class' => 'span5'
		));?>
	</div>
</div>
<div class="modal-footer">
	<?= $this->Form->submit(__d('synchro_dossier', 'Renommer'), array('class' => 'btn')); ?>
	<?= $this->Form->end(); ?>
</div>
