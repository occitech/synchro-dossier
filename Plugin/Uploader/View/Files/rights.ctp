<?php echo $this->Html->css('Uploader.style'); ?>
<?php echo $this->Html->script('Uploader.app'); ?>

<div class="rights uploader">
	<div class="add-right">
		<?= 
			$this->Form->create('User', array('url' => array(
				'controller' => 'Files',
				'action' => 'toggleRight',
				$acos['Aco']['foreign_key']
			))) .
			$this->Form->input('user_id') .
			$this->Form->end(__('Add user'));
		?>
	</div>
 	<table>
		<tr>
			<td><?= __('Name') ?></td>
			<td><?= __('Read') ?></td>
			<td><?= __('Write') ?></td>
			<td><?= __('Delete') ?></td>
			<td><?= __('Change_right') ?></td>
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
			<td><?= $this->Layout->status(1); ?></td>
			<td></td>
		</tr>
		<?php endforeach ?>

		<?php foreach ($acos['Aro'] as $aro): ?>
		<?php $hasRightToChangeRight = $this->Acl->userCanChangeRight(
				AuthComponent::user('id'),
				AuthComponent::user('role_id'),
				$aro['User']['role_id'],
				$folder['User']['id']
		); ?>
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
						$this->Layout->status($aro['ArosAco']['_change_right']),
						array(
							'action' => 'toggleRight',
							$acos['Aco']['foreign_key'],
							$aro['foreign_key'],
							'change_right'
						),
						array('escape' => false)
					); ?>
				<?php else: ?>
					<?= $this->Layout->status($aro['ArosAco']['_change_right']) ?>
				<?php endif ?>
			</td>
			<td>

				<?php if ($hasRightToChangeRight): ?>
					<?= $this->Html->link(
						__('Remove all rights'),
						array(
							'action' => 'removeRight',
							$aro['ArosAco']['aco_id'],
							$aro['ArosAco']['aro_id']
						)
					); ?>
				<?php endif ?>
			</td>
		</tr>
		<?php endforeach ?>
	</table>		
</div>