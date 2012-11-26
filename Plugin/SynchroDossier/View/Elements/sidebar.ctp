	<ul class="nav nav-list">
		<li class="nav-header">
			<?= $this->SynchroDossier->displayQuota(); ?>
		</li>
		<li class="nav-header">
			<?= __('Mes Dossiers'); ?>
			<span style="float: right;">
				<a href="#" rel="tooltip" title="<?= __('Ajouter un dossier'); ?>">
					<i class="icon-plus-sign"></i>
				</a>
			</span>
		</li>
		<?php foreach ($SynchroDossier_rootFolders as $folder): ?>
			<li>
				<?php if ($this->Acl->userCan($folder['Aco'], 'read')): ?>
					<?= $this->Html->link(
						$folder['UploadedFile']['filename'],
						array('plugin' => 'uploader', 'controller' => 'files', 'action' =>'browse', $folder['UploadedFile']['id'])
					); ?>
				<?php endif ?>
			</li>
		<?php endforeach ?>
		<?php if (!is_null($SynchroDossier_aroAccessFolder)): ?>
			<li class="nav-header">
				<?= __('Utilisateurs du dossier'); ?>
				<span style="float: right;">
					<a href="#" rel="tooltip" title="<?= __('Ajouter un utilisateur à ce dossier'); ?>">
						<i class="icon-plus-sign"></i>
					</a>
				</span>
			</li>
			<?php foreach ($SynchroDossier_aroAccessFolder as $aro): ?>
				<li>
					<a href="#"><?= $aro['User']['username']; ?></a>
				</li>
			<?php endforeach ?>
		<?php endif ?>

	</ul>