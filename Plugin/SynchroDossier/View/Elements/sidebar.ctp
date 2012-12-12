	<ul class="nav nav-list">
		<?php if ($can['canCreateUser']()): ?>
			<li class="nav-header">
				<?= __('Votre Quota'); ?>
			</li>
			<li>
				<?= $this->SynchroDossier->displayQuota(); ?>
			</li>
		<?php endif ?>
		<li class="nav-header">
			<?= __('Mes Dossiers'); ?>
			<?php if ($can['canCreateUser']()): ?>
				<span style="float: right;">
					<a href="#addSharingModal" role="button" data-toggle="modal" rel="tooltip" title="<?= __('Ajoutez un dossier'); ?>">
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
			<?= __('Actions') ?>
		</li>
		<?php if ($can['canCreateUser']()): ?>
			<li>
				<?= $this->Html->link(
					__('Créez un utilisateur'),
					array(
						'admin' => true,
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
						__('Créez un sous dossier'),
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
			<?= __('Recherche') ?>
		</li>
		<li class="search">
		<?=
			$this->Form->create(
				'UploadedFile',
				array('url' => array('controller' => 'files', 'action' => 'find', $folderId))
			) .
			$this->Form->input(
				'parent_id',
				array(
					'label' => false,
					'multiple' => 'checkbox',
					'options' => array($folderId => __('Rechercher dans ce dossier')),
				)
			) .
			$this->Form->input(
				'filename',
				array('label' => false, 'placeholder' => __('Nom du fichier'), 'class' => 'span12')
			) .
			$this->Form->input(
				'is_folder',
				array(
					'label' => false,
					'multiple' => 'checkbox',
					'options' => array(0 => __('Fichiers'), 1 => __('Dossiers')),
				)
			) .
			$this->Form->input(
				'username',
				array('label' => false, 'placeholder' => __('Utilisateur'), 'class' => 'span12')
			) .
			$this->Form->input(
				'size_min',
				array('label' => false, 'placeholder' => __('Taille min - ko'), 'class' => 'span6 placeholder-size', 'div' => false)
			) .
			$this->Form->input(
				'size_max',
				array('label' => false, 'placeholder' => __('Taille max - ko'), 'class' => 'span6 placeholder-size', 'div' => false)
			) .
			$this->Form->input(
				'created_min',
				array('label' => false, 'placeholder' => __('Date min'), 'class' => 'span6 placeholder-size', 'id' => 'search-from', 'div' => false)
			) .
			$this->Form->input(
				'created_max',
				array('label' => false, 'placeholder' => __('Date max'), 'class' => 'span6 placeholder-size', 'id' => 'search-to', 'div' => false)
			) .
			$this->Form->submit(__('Rechercher'), array('class' => 'btn span12', 'div' => false)) .
			$this->Form->end();
		?>
		</li>	

		<?php if (!empty($SynchroDossier_aroAccessFolder) && isset($folderId)): ?>
			<?php if ($this->UploaderAcl->userCan('change_right')): ?>
				<li class="nav-header">
					<?= __('Utilisateurs du dossier'); ?>
						<span style="float: right;">
							<?= $this->Html->link(
								'<i class="icon-plus-sign"></i>',
								array('controller' => 'files', 'action' => 'rights', $folderId),
								array(
									'data-toggle' => 'modal',
									'rel' => 'tooltip',
									'title' => __('Ajoutez un utilisateur à ce dossier'),
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