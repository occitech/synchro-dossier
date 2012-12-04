<div id="createFolderModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?= __('Créez un sous dossier'); ?></h3>
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
				'placeholder' => __('Nom du sous dossier'),
				'class' => 'span7'
			));?>
		</div>
	</div>
	<div class="modal-footer">
		<?= $this->Form->submit(__('Créez le sous dossier'), array('class' => 'btn')); ?>
		<?= $this->Form->end(); ?>
	</div>
</div>
