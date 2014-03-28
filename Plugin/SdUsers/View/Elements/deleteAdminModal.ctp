<div id="deleteAdminModal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<?php if (isset($admins)) :?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?= __d('sd_users', 'Supprimer un admin'); ?></h3>
	</div>
		<?php if (count($admins) > 1) :?>
		<div class="modal-body">
			<p><?= __d('sd_users', 'Veuillez choisir un nouvel admin à qui transmettre les dossiers de l\'admin que vous voulez supprimer.');?></p>
			<?= $this->Form->create('User', array(
				'url' =>array(
					'controller' => 'sd_users',
					'action' => 'deleteAdmin'),
				'type' => 'POST'),
				array('class' => 'form-inline')
			) . $this->Chosen->select(
				'new_admin_id',
				$admins,
				array('data-placeholder' => __d('sd_users', 'Select user'))
			);?>
	 		<input name="data[User][old_admin_id]" id="oldAdminId" type="hidden" value="8">
		</div>
		<div class="modal-footer">
			<?= $this->Form->submit(__d('sd_users', 'Supprimer'), array('class' => 'btn')); ?>
			<?= $this->Form->end(); ?>
		</div>
		<?php else : ?>
			<div class="modal-body">
				<p><?= __d('sd_users', 'Cet administrateur est le dernier disponible, vous ne pouvez pas transmettre ses dossiers à un autre administrateur.');?></p>
				<?= $this->Form->create('User', array(
					'url' =>array(
						'controller' => 'sd_users',
						'action' => 'delete',
						key($admins)),
					'type' => 'GET'),
					array('class' => 'form-inline')
				);?>
			</div>
			<div class="modal-footer">
				<?= $this->Form->submit(__d('sd_users', 'Supprimer'), array('class' => 'btn')); ?>
				<?= $this->Form->end(); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
<script>
$(document).on('click', '.open-deleteAdminModal', function () {
	var oldAdminId = $(this).data('admin-id');
	$("#deleteAdminModal #oldAdminId").val(oldAdminId);
	$("#UserNewAdminId option[value='" + oldAdminId + "']").addClass("hidden");
});

$(document).on('hidden', '#deleteAdminModal', function () {
	oldId = $('#oldAdminId').val();
	$("#UserNewAdminId option[value='" + oldId + "']").removeClass("hidden");
})
</script>
