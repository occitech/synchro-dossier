	<ul class="nav nav-list">
		<?php if ($can['canCreateUser']()): ?>
			<li class="nav-header">
				<?= __d('SynchroDossier', 'Votre Quota'); ?>
			</li>
			<li>
				<?= $this->SynchroDossier->displayQuota(); ?>
			</li>
		<?php endif ?>
		<li class="nav-header">
			<?= __d('SynchroDossier', 'Mes Dossiers'); ?>
			<?php if ($can['canCreateUser']()): ?>
				<span style="float: right;">
					<a href="#addSharingModal" role="button" data-toggle="modal" rel="tooltip" title="<?= __d('SynchroDossier', 'Ajoutez un dossier'); ?>">
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
			<?= __d('SynchroDossier', 'Actions') ?>
		</li>
		<?php if ($can['canCreateUser']()): ?>
			<li>
				<?= $this->Html->link(
					__d('SynchroDossier', 'Créez un utilisateur'),
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
						__d('SynchroDossier', 'Créez un sous dossier'),
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
			<?= __d('SynchroDossier', 'Recherche') ?>
		</li>
		<li class="search">
			<?= $this->element('Uploader.search'); ?>
		</li>	

		<?php if (!empty($SynchroDossier_aroAccessFolder) && isset($folderId)): ?>
			<?php if ($this->UploaderAcl->userCan($folderAco['Aco'], 'change_right')): ?>
				<li class="nav-header">
					<?= __d('SynchroDossier', 'Utilisateurs du dossier'); ?>
						<span style="float: right;">
							<?= $this->Html->link(
								'<i class="icon-plus-sign"></i>',
								array('controller' => 'files', 'action' => 'rights', $folderId),
								array(
									'data-toggle' => 'modal',
									'rel' => 'tooltip',
									'title' => __d('SynchroDossier', 'Ajoutez un utilisateur à ce dossier'),
									'escape' => false
								)
							); ?>
						</span>
				</li>
				<?php foreach ($SynchroDossier_aroAccessFolder as $aro): ?>
					<li>
						<a href="#"><?= $aro['User']['username']; ?></a>
					</li>
				<?php endforeach ?>
			<?php endif ?>
		<?php endif ?>
	</ul>