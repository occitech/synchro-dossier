	<ul class="nav nav-list">
		<?php if ($can['canCreateUser']()): ?>
			<li class="nav-header">
				<?= __d('synchro_dossier', 'Votre Quota'); ?>
			</li>
			<li>
				<?= $this->SynchroDossier->displayQuota(); ?>
			</li>
		<?php endif ?>
		<li class="nav-header">
			<?= __d('synchro_dossier', 'Mes Dossiers'); ?>
			<?php if ($can['canCreateUser']()): ?>
				<span style="float: right;">
					<a href="#addSharingModal" role="button" data-toggle="modal" rel="tooltip" title="<?= __d('synchro_dossier', 'Ajoutez un dossier'); ?>">
						<i class="icon-plus-sign"></i>
					</a>
				</span>
			<?php endif ?>
		</li>
		<div class="sidebar-folders">
			<ul>
				<?= $this->SynchroDossier->displayTreeFolders($SynchroDossier_allFolders); ?>
			</ul>
		</div>

		<li class="nav-header">
			<?= __d('synchro_dossier', 'Actions') ?>
		</li>
		<?php if ($can['canCreateUser']()): ?>
			<li>
				<?= $this->Html->link(
					__d('synchro_dossier', 'Créez un utilisateur'),
					array(
						'plugin' => 'sd_users',
						'controller' => 'sd_users',
						'action' => 'add'
					),
					array('class' => 'btn')
				);
				?>
			</li>
		<?php endif ?>
		<?php if (isset($folderId)): ?>
			<?php if ($this->UploaderAcl->userCan($folderAco['Aco'], 'create')): ?>
				<li>
					<?= $this->Html->link(
						__d('synchro_dossier', 'Créez un sous dossier'),
						'#createFolderModal',
						array(
							'class' => 'btn',
							'role' => 'button',
							'data-toggle' => 'modal',
						)
					);
					?>
				</li>
			<?php endif ?>
		<?php endif ?>

		<li class="nav-header">
			<?= __d('synchro_dossier', 'Recherche') ?>
		</li>
		<li class="search">
			<?= $this->element('Uploader.search'); ?>
		</li>

		<?php if (!empty($SynchroDossier_aroAccessFolder) && isset($folderId)): ?>
			<?php if ($this->UploaderAcl->userCan($folderAco['Aco'], 'change_right')): ?>
				<li class="nav-header">
					<?= __d('synchro_dossier', 'Utilisateurs du dossier'); ?>
						<span style="float: right;">
							<?= $this->Html->link(
								'<i class="icon-plus-sign"></i>',
								array('controller' => 'files', 'action' => 'rights', $folderId),
								array(
									'data-toggle' => 'modal',
									'rel' => 'tooltip',
									'title' => __d('synchro_dossier', 'Ajoutez un utilisateur à ce dossier'),
									'escape' => false
								)
							); ?>
						</span>
				</li>
				<?php foreach ($SynchroDossier_aroAccessFolder as $aro): ?>
					<li>
						<?= $this->Html->link(
							$aro['User']['username'],
							array(
								'plugin' => 'sd_users',
								'controller' => 'sd_users',
								'action' => 'edit', 
								$aro['User']['id']
							)
						); ?>
					</li>
				<?php endforeach ?>
				<li class="nav-header">
					<?= __d('synchro_dossier', 'Super Administrateurs'); ?>
				</li>
				<?php foreach ($superAdmins as $superAdmin): ?>
				<li>
					<?= $this->Html->link(
						$superAdmin['User']['username'],
						array(
							'plugin' => 'sd_users',
							'controller' => 'sd_users',
							'action' => 'edit', 
							$superAdmin['User']['id']
						)
					); ?>
				</li>
				<?php endforeach ?>
			<?php endif ?>
		<?php endif ?>
	</ul>