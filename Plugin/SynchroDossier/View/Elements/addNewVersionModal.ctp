<div id="addNewVersion" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?= __('Ajouter une nouvelle version'); ?></h3>
	</div>
	<div class="modal-body no-overflow-y">
		<div class="uploader">
TODO
		</div>
	</div>
	<div class="modal-footer">
		<?= $this->Form->submit(__('Ajouter une nouvelle version'), array('class' => 'btn')); ?>
		<?= $this->Form->end(); ?>
	</div>
</div>
