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
	</ul>
	<div class="sidebar-folders">
		<ul class="nav nav-list">
			<?= $this->SynchroDossier->displayTreeFolders($SynchroDossier_allFolders); ?>
		</ul>
	</div>
	<ul class="nav nav-list">
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