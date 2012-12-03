<div class="rights uploader">
	<div class="add-right">
		<?= 
			$this->Form->create('User', array('url' => array(
				'controller' => 'Files',
				'action' => 'toggleRight',
				$acos['Aco']['foreign_key']
			)), array('class' => 'form-inline')) .
			$this->Chosen->select(
				'user_id',
				$usersNotInFolder,
				array('data-placeholder' => __('Select user'))
			) .
			$this->Form->submit(__('Add user'), array('class' => 'btn')) .
			$this->Form->end();
		?>
	</div>
 	<table class="table table-hover">
		<tr>
			<td><?= __('Name') ?></td>
			<td><?= __('Lecture') ?></td>
			<td><?= __('Upload') ?></td>
			<td><?= __('Suppression') ?></td>
			<td><?= __('Actions') ?></td>
		</tr>
		<?php foreach ($superAdmins as $superAdmin): ?>
		<tr>
			<td>
				<?= $superAdmin['User']['username']; ?>
				<span class="label label-SuperAdmin">
					<?= $superAdmin['Role']['title']; ?>
				</span>
			</td>
			<td><?= $this->Layout->status(1); ?></td>
			<td><?= $this->Layout->status(1); ?></td>
			<td><?= $this->Layout->status(1); ?></td>
			<td></td>
		</tr>
		<?php endforeach ?>

		<?php foreach ($acos['Aro'] as $aro): ?>
			<?php
				$hasRightToChangeRight = $can['canChangeRight'](
					$aro['User']['id'],
					$aro['User']['role_id'],
					$folder['User']['id']
				); 
				$listRolesToPrint = array(
					Configure::read('sd.Admin.roleId'),
					Configure::read('sd.Utilisateur.roleId'),
				);
			?>
			<?php if (in_array($aro['User']['role_id'], $listRolesToPrint)): ?>
				<tr>
					<td>
						<?= $aro['alias'] ?>
						<span class="label label-<?= $aro['alias'] ?>">
							<?= $aro['User']['Role']['title']; ?>
						</span>
					</td>
					<td>
						<?php if ($hasRightToChangeRight): ?>
							<?= $this->Html->link(
								$this->Layout->status($aro['ArosAco']['_read']),
								array(
									'action' => 'toggleRight',
									$acos['Aco']['foreign_key'],
									$aro['foreign_key'],
									'read'
								),
								array('escape' => false)
							); ?>
						<?php else: ?>
							<?= $this->Layout->status($aro['ArosAco']['_read']) ?>
						<?php endif ?>
					</td>
					<td>
						<?php if ($hasRightToChangeRight): ?>
							<?= $this->Html->link(
								$this->Layout->status($aro['ArosAco']['_create']),
								array(
									'action' => 'toggleRight',
									$acos['Aco']['foreign_key'],
									$aro['foreign_key'],
									'create'
								),
								array('escape' => false)
							); ?>
						<?php else: ?>
							<?= $this->Layout->status($aro['ArosAco']['_create']) ?>
						<?php endif ?>
					</td>
					<td>
						<?php if ($hasRightToChangeRight): ?>
							<?= $this->Html->link(
								$this->Layout->status($aro['ArosAco']['_delete']),
								array(
									'action' => 'toggleRight',
									$acos['Aco']['foreign_key'],
									$aro['foreign_key'],
									'delete'
								),
								array('escape' => false)
							); ?>
						<?php else: ?>
							<?= $this->Layout->status($aro['ArosAco']['_delete']) ?>
						<?php endif ?>
					</td>
					<td>

						<?php if ($hasRightToChangeRight): ?>
							<?= $this->Html->link(
								__('Supprimer l\'utilisateur de ce dossier'),
								array(
									'action' => 'removeRight',
									$aro['ArosAco']['aco_id'],
									$aro['ArosAco']['aro_id']
								)
							); ?>
						<?php endif ?>
					</td>
				</tr>
			<?php endif ?>
		<?php endforeach ?>
	</table>		
</div>