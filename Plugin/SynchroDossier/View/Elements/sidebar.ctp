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
				<?= $this->Html->link(
					$folder['UploadedFile']['filename'],
					array('plugin' => 'uploader', 'controller' => 'files', 'action' =>'browse', $folder['UploadedFile']['id'])
				); ?>
			</li>
		<?php endforeach ?>
		<li class="nav-header">
			<?= __('Utilisateurs'); ?>
			<span style="float: right;">
				<a href="#" rel="tooltip" title="<?= __('Ajouter un utilisateur à ce dossier'); ?>">
					<i class="icon-plus-sign"></i>
				</a>
			</span>
		</li>
		<li><a href="#">Aymeric Derbois</a></li>
		<li><a href="#">Home</a></li>
		<li><a href="#">Home</a></li>
		<li><a href="#">Home</a></li>
		<li><a href="#">Home</a></li>
		<li><a href="#">Home</a></li>
		<li><a href="#">Library</a></li>
	</ul>