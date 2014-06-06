<?php
	$this->start('navbar');
		echo $this->element('SynchroDossier.navbar');
	$this->end();
?>
<?php $this->start('sidebar'); ?>
	<?php if (isset($can)): ?>
		<?= $this->element('SynchroDossier.sidebar', array('can' => $can)); ?>
	<?php endif ?>
<?php $this->end();	?>


<div class="rights uploader">
	<h2><?= __d('uploader', 'Share "%s"', $folder['UploadedFile']['filename']) ?></h2>
	<div class="add-right">
		<?=
			$this->Form->create('User', array('url' => array(
				'controller' => 'Files',
				'action' => 'toggleRight',
				$acos['Aco']['foreign_key']
			), 'type' => 'GET'), array('class' => 'form-inline')) .
			$this->Chosen->select(
				'user_id',
				$usersNotInFolder,
				array('data-placeholder' => __d('uploader', 'Select user'))
			) .
			$this->Form->submit(__d('uploader', 'Share'), array('class' => 'btn', 'data-event' => 'ga', 'data-category' => 'Partager', 'data-action' => 'click')) .
			$this->Form->end();
		?>
	</div>
	<table class="table table-hover">
		<tr>
			<td><?= __d('uploader', 'Name') ?></td>
			<td><?= __d('uploader', 'Lire') ?></td>
			<td><?= __d('uploader', 'Écrire') ?></td>
			<td><?= __d('uploader', 'Supprimer') ?></td>
			<td><?= __d('uploader', 'Renommer') ?></td>
			<td><?= __d('uploader', 'Toggle mail') ?></td>
			<td><?= __d('uploader', 'Actions') ?></td>
		</tr>
		<?php foreach ($superAdmins as $superAdmin): ?>
		<tr>
			<td>
				<?= $superAdmin['User']['name']; ?>
				<span class="label label-SuperAdmin">
					<?= $superAdmin['Role']['title']; ?>
				</span>
			</td>
			<td colspan="6"><?= __d('uploader', 'This User has all rights on this folder'); ?></td>
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
						<?= $aro['User']['Profile']['full_name'] ?>
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
								$this->Layout->status($aro['ArosAco']['_update']),
								array(
									'action' => 'toggleRight',
									$acos['Aco']['foreign_key'],
									$aro['foreign_key'],
									'update'
								),
								array('escape' => false)
							); ?>
						<?php else: ?>
							<?= $this->Layout->status($aro['ArosAco']['_update']) ?>
						<?php endif ?>
					</td>
					<td>
						<?php if ($hasRightToChangeRight): ?>
							<?= $this->Html->link(
								$this->Layout->status(array_key_exists($aro['User']['id'], $userRegisteredToAlert)),
								array(
									'action' => 'toggleEmailSubscription',
									$folderId,
									$aro['User']['id']
								),
								array('escape' => false)
							); ?>
						<?php else: ?>
							<?= $this->Layout->status(array_key_exists($aro['User']['id'], $userRegisteredToAlert)) ?>
						<?php endif ?>
					</td>

					<td>
						<?php if ($hasRightToChangeRight): ?>
							<?= $this->Html->link(
								'<i class="icon-trash"></i>',
								array(
									'action' => 'removeRight',
									$folderId,
									$aro['ArosAco']['aco_id'],
									$aro['ArosAco']['aro_id']
								),
								array(
									'rel' => 'tooltip',
									'escape' => false,
									'title' => __d('uploader', 'Ne plus partager avec cet utilisateur')
								)
							); ?>
						<?php endif ?>
					</td>
				</tr>
			<?php endif ?>
		<?php endforeach ?>
	</table>
</div>
