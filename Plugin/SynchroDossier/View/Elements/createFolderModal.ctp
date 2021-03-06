<div id="createFolderModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?= __d('synchro_dossier', 'Créer un sous dossier'); ?></h3>
	</div>
	<div class="modal-body no-overflow-y">
		<div class="uploader">
			<?= $this->Form->create('UploadedFile',	array(
				'type' => 'post',
				'url' => array(
					'plugin' => 'uploader',
					'controller' => 'files',
					'action' => 'createFolder',
					$folderId
				)
			)); ?>

			<?= $this->Form->input('UploadedFile.filename', array(
				'label' => false,
				'placeholder' => __d('synchro_dossier', 'Nom du sous dossier'),
				'class' => 'span5'
			));?>
		</div>
	</div>
	<div class="modal-footer">
		<?= $this->Form->submit(__d('synchro_dossier', 'Créer le sous dossier'), array('class' => 'btn', 'data-event' => 'ga', 'data-category' => 'Créer un dossier', 'data-action' => 'click')); ?>
		<?= $this->Form->end(); ?>
	</div>
</div>
