<div id="addSharingModal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?= __d('SynchroDossier', 'Créez un dossier'); ?></h3>
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
				'class' => 'span7',
				'placeholder' => __d('SynchroDossier', 'Nom du dossier'),
				'label' => false
			)
		);?>
	</div>
	<div class="modal-footer">
		<?= $this->Form->submit(__d('SynchroDossier', 'Créez un dossier'), array('class' => 'btn')); ?>
		<?= $this->Form->end(); ?>
	</div>
</div>