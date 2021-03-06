<?php
	$this->start('navbar');
		echo $this->element('SynchroDossier.navbar');
	$this->end();
?>

<?php echo $this->Html->link(__d('sd_users', 'Créer un utilisateur'), array('plugin' => 'sd_users', 'action' => 'add'), array('class' => 'btn', 'style' => 'margin-bottom:15px')) ?>
<div class="row-fluid">
	<div class="span12">
<?php if(!empty($users)):?>
		<table class="table table-striped">
			<?php
				$tableHeaders =  $this->Html->tableHeaders(array(
					$this->Paginator->sort('name', __d('sd_users', 'Nom')),
					$this->Paginator->sort('firstname', __d('sd_users', 'Prénom')),
					$this->Paginator->sort('email', __d('sd_users', 'Email')),
					$this->Paginator->sort('role', __d('sd_users', 'Role')),
					__d('sd_users', 'Actions'),
				));
				echo $tableHeaders;
				$rows = array();
				foreach ($users as $user) :
					$actions  = $this->Html->link(
						'',
						'mailto:' . $user['User']['email'],
						array('icon' => 'envelope', 'tooltip' => __d('sd_users', 'Envoyer un email à cet utilisateur'))
					);
					$actions .= ' ' . $this->Croogo->adminRowActions($user['User']['id']);
					if ($can['canUpdateUser']($user['User'])) :
						$actions .= ' ' . $this->Html->link(
							'',
							array('action' => 'edit', $user['User']['id']),
							array('icon' => 'pencil', 'tooltip' => __d('sd_users', 'Modifier cet utilisateur'))
						);
						if ($user['User']['role_id'] == SdUser::ROLE_ADMIN_ID) {
							$actions .= ' ' . $this->Html->link(
								'',
								'#deleteAdminModal',
								array(
									'icon' => 'remove',
									'tooltip' => __d('sd_users', 'Supprimer cet utilisateur'),
									'class' => 'red open-deleteAdminModal',
									'escape' => false,
									'data-toggle' => 'modal',
									'data-admin-id' => $user['User']['id']
								)
							);
						} else {
							$actions .= ' ' . $this->Form->postLink(
								'',
								array('action' => 'delete',	$user['User']['id']),
								array(
									'icon' => 'trash',
									'tooltip' => __d('sd_users', 'Supprimer cet utilisateur'),
									'class' => 'red',
									'escape' => false
								),
								__d('sd_users', 'Are you sure?')
							);
						}

					endif;

					$rows[] = array(
						$user['Profile']['name'],
						$user['Profile']['firstname'],
						$user['User']['email'],
						$user['Role']['title'],
						$actions,
					);
				endforeach;

				echo $this->Html->tableCells($rows);
				echo $tableHeaders;
			?>
		</table>
		<?php else: ?>
			<p class="alert-error alert">
				<button class="close" data-dismiss="alert" type="button">×</button>
				<?php echo __d('sd_users', 'No users found') ;?>
			</p>
		<?php endif; ?>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<?php if ($this->elementExists('SynchroDossier.paging')): ?>
			<?php echo $this->element('SynchroDossier.paging'); ?>
		<?php else: ?>
			<?php if (isset($this->Paginator) && isset($this->request['paging'])): ?>
				<div class="pagination">
					<p>
					<?php
					echo $this->Paginator->counter(array(
						'format' => __d('croogo', 'Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
					));
					?>
					</p>
					<ul>
						<?php echo $this->Paginator->first('< ' . __d('croogo', 'first')); ?>
						<?php echo $this->Paginator->prev('< ' . __d('croogo', 'prev')); ?>
						<?php echo $this->Paginator->numbers(); ?>
						<?php echo $this->Paginator->next(__d('croogo', 'next') . ' >'); ?>
						<?php echo $this->Paginator->last(__d('croogo', 'last') . ' >'); ?>
					</ul>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>
