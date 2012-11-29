	<ul class="nav nav-list">
		<li class="nav-header">
			<?= __('Votre Quota'); ?>
		</li>
		<li>
			<?= $this->SynchroDossier->displayQuota(); ?>
		</li>

		<li class="nav-header">
			<?= __('Mes Dossiers'); ?>
			<span style="float: right;">
				<a href="#addSharingModal" role="button" data-toggle="modal" rel="tooltip" title="<?= __('Ajouter un dossier'); ?>">
					<i class="icon-plus-sign"></i>
				</a>
			</span>
		</li>
		<div class="sidebar-folders">
			<ul>
				<?= $this->SynchroDossier->displayTreeFolders($SynchroDossier_allFolders); ?>
			</ul>
		</div>

		<li class="nav-header">
			<?= __('Actions') ?>
		</li>
		<?php if ($can['canCreateUser']()): ?>
			<li>
				<?= $this->Html->link(
					__('Créer un utilisateur'),
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
			<li>
				<?= $this->Html->link(
					__('Créer un sous dossier'),
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

		<?php if (!empty($SynchroDossier_aroAccessFolder)): ?>
			<?php if ($this->Acl->userCan('change_right')): ?>				
				<li class="nav-header">
					<?= __('Utilisateurs du dossier'); ?>
						<span style="float: right;">
							<a href="#addUserRightsOnFolder" role="button" data-toggle="modal" rel="tooltip" title="<?= __('Ajouter un utilisateur à ce dossier'); ?>">
								<i class="icon-plus-sign"></i>
							</a>
							</a>
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