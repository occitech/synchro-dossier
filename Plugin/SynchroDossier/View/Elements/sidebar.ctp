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
			<?= $this->Html->link(
				__d('synchro_dossier', 'Mes Dossiers'),
				array(
					'plugin' => 'uploader',
					'controller' => 'files',
					'action' => 'browse'
				),
				array('class' => 'home')
			);
			?>
		</li>
		<?php $currentFolderId = isset($folderId0) ? $folderId : null;?>
		<div class="sidebar-folders" data-current-folder-id="<?= $folderId;?>">
			<ul>
				<?= $this->SynchroDossier->displayTreeFolders($SynchroDossier_allFolders); ?>
			</ul>
		</div>

		<li class="nav-header">
			<?= __d('synchro_dossier', 'Actions') ?>
		</li>
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
			<?php if ($this->UploaderAcl->userCan($folderAco['Aco'], 'change_right')): ?>
				<li>
					<?= $this->Html->link(
						__d('synchro_dossier', 'Partager ce dossier'),
						array(
							'plugin' => 'uploader',
							'controller' => 'files',
							'action' => 'rights',
							$folderId
						),
						array('class' => 'btn')
					);
					?>
				</li>
			<?php endif ?>
		<?php else: ?>
			<?php if ($this->UploaderAcl->userCanCreateSharing()): ?>
				<li>
					<?= $this->Html->link(
						__d('synchro_dossier', 'Créer un dossier'),
						'#addSharingModal',
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

		<?php if ($this->Session->read('Auth.User.Role.alias') != 'sdUtilisateur' && !empty($SynchroDossier_aroAccessFolder) && isset($folderId)): ?>
			<?php if ($this->UploaderAcl->userCan($folderAco['Aco'], 'change_right')): ?>
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
