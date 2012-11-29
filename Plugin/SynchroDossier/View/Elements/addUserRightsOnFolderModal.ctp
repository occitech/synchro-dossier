<div id="addUserRightsOnFolder" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?= __('Select User'); ?></h3>
	</div>
	<div class="modal-body no-overflow-y">
		<?=	$this->Form->create('User', array('url' => array(
			'controller' => 'Files',
			'action' => 'toggleRight',
			$folderId
		))); ?>
		<?= 
			$this->Chosen->select(
				'user_id',
				array(),
				array('data-placeholder' => __('Select user'))
			);
		?>
	</div>
	<div class="modal-footer">
		<?= $this->Form->submit(__('Add user on this folder'), array('class' => 'btn')); ?>
		<?= $this->Form->end(); ?>
	</div>
</div>