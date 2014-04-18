<div id="addNewVersion" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?= __d('synchro_dossier', 'Ajouter une nouvelle version de'); ?> <span class="filename"></span></h3>
	</div>
	<div class="modal-body no-overflow-y">
		<div class="uploader">
			<?php echo $this->Form->create('UploadedFile', array('type' => 'file')); ?>

			<?php echo $this->Form->input('file', array('type' => 'file'));?>
		</div>
	</div>
	<div class="modal-footer">
		<?= $this->Form->submit(__d('synchro_dossier', 'Ajouter une nouvelle version'), array('class' => 'btn', 'data-event' => 'ga', 'data-category' => 'Nouvelle version', 'data-action' => 'click',)); ?>
		<?= $this->Form->end(); ?>
	</div>
</div>
