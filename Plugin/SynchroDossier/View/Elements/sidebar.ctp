	<ul class="nav nav-list">
		<?php if ($can['canCreateUser']()): ?>
			<li class="nav-header">
				<?= __d('synchro_dossier', 'Votre Quota'); ?>
				<?php if(Configure::read('sd.tooltip.quota')):?>
					<i class="icon-info-sign"
					   data-toggle="popover"
					   data-title=""
					   data-trigger="<?= Configure::read('sd.tooltip.quota.trigger');?>"
					   data-original-title="<?= Configure::read('sd.tooltip.quota.title');?>"
					   data-content="<?= Configure::read('sd.tooltip.quota.text');?>"
					   data-placement="<?= Configure::read('sd.tooltip.quota.position');?>"
					   data-event="ga"
					   data-category="Infobulles"
					   data-action="hover"
					   data-label="Quota"
					></i>
				<?php endif; ?>
			</li>
			<li>
				<?= $this->SynchroDossier->displayQuota(); ?>
			</li>
		<?php endif ?>
		<li class="nav-header">
			<?= $this->Html->link(
				__d('synchro_dossier', 'Partages'),
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

		<?php if (
			(
				isset($folderId)
				&& (
					$this->UploaderAcl->userCanCreateSubdirectory($folderAco)
					|| (!$this->SynchroDossier->hasUserRole(CakeSession::read('Auth.User.role_id')) && $this->UploaderAcl->userCanShareDirectory($folderAco))
				)
			)
			|| $this->UploaderAcl->userCanCreateSharing()
		): ?>
			<li class="nav-header">
				<?= __d('synchro_dossier', 'Actions') ?>
			</li>
			<?php if (isset($folderId)): ?>
				<?php if ($this->UploaderAcl->userCanCreateSubdirectory($folderAco)): ?>
					<li>
						<?= $this->Html->link(
							__d('synchro_dossier', 'Créer un sous dossier'),
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
				<?php if (!$this->SynchroDossier->hasUserRole(CakeSession::read('Auth.User.role_id')) && $this->UploaderAcl->userCanShareDirectory($folderAco)): ?>
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
			<?php elseif ($this->UploaderAcl->userCanCreateSharing()): ?>
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

		<?php if (!$this->SynchroDossier->hasUserRole(CakeSession::read('Auth.User.role_id')) && !$this->SynchroDossier->hasAdminRole(CakeSession::read('Auth.User.role_id')) && !empty($SynchroDossier_aroAccessFolder) && isset($folderId)): ?>
			<?php if ($this->UploaderAcl->userCan($folderAco['Aco'], 'change_right')): ?>
				<li class="nav-header">
					<?= __d('synchro_dossier', 'Super Administrateurs'); ?>
				</li>
				<?php foreach ($superAdmins as $superAdmin): ?>
				<li>
					<?= $this->Html->link(
						$superAdmin['User']['name'],
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
