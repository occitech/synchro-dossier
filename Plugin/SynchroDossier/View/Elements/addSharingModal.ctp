<div id="addSharingModal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?= __d('synchro_dossier', 'Créez un dossier'); ?></h3>
	</div>
	<div class="modal-body">
		<?= $this->Form->create('UploadedFile', array('url' => array(
			'plugin' => 'uploader',
			'controller' => 'files',
			'action' => 'createSharing'
		))); ?>
		<?= $this->Form->input(
			'UploadedFile.filename',
			array(
				'class' => 'span5',
				'placeholder' => __d('synchro_dossier', 'Nom du dossier'),
				'label' => false
			)
		);?>
		<?= $this->Form->input(
			'SdAlertEmail.subscribe',
			array(
				'label' => __d('uploader', 'Inscription aux Alertes email'),
				'type' => 'checkbox'
			)
		); ?>
	</div>
	<div class="modal-footer">
		<?= $this->Form->submit(__d('synchro_dossier', 'Créez un dossier'), array('class' => 'btn')); ?>
		<?= $this->Form->end(); ?>
	</div>
</div>
