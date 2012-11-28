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
		<?php if (isset($folderId)): ?>
			<?= $this->Html->link(
				__('Créer un sous dossier'),
				array('controller' => 'files', 'action' => 'createFolder', $folderId));
			?>
		<?php endif ?>
		<li>
		</li>

		<?php if (!empty($SynchroDossier_aroAccessFolder)): ?>
			<li class="nav-header">
				<?= __('Utilisateurs du dossier'); ?>
				<?php if ($this->Acl->userCan('change_right')): ?>				
					<span style="float: right;">
						<a href="#addUserRightsOnFolder" role="button" data-toggle="modal" rel="tooltip" title="<?= __('Ajouter un utilisateur à ce dossier'); ?>">
							<i class="icon-plus-sign"></i>
						</a>
						</a>
					</span>
				<?php endif ?>
			</li>
			<?php foreach ($SynchroDossier_aroAccessFolder as $aro): ?>
				<li>
					<a href="#"><?= $aro['User']['username']; ?></a>
				</li>
			<?php endforeach ?>
		<?php endif ?>
	</ul>