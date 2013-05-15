<div class="navbar">
	<div class="navbar-inner">
		<ul class="nav">
			<li><?= $this->Html->link(__d('SynchroDossier', 'Espace client'), '/', array('class' => 'brand')); ?></li>
			<li <?php if($this->SynchroDossier->routeIsActive(array(
				'plugin' => 'uploader',
				'controller' => 'files',
				'action' => 'browse'
			))) {echo 'class="active"';}?>><?= $this->Html->link(__d('SynchroDossier', 'Accueil'), '/'); ?></li>

			<?php if ($this->SdUsers->isAdmin()): ?>
				<li <?php if($this->SynchroDossier->routeIsActive(array(
					'plugin' => 'sd_users',
					'controller' => 'sd_users',
					'action' => 'index'
				))) {echo 'class="active"';}?>><?= $this->Html->link(__d('SynchroDossier', 'Liste des Utilisateurs'), array(
					'plugin' => 'sd_users',
					'controller' => 'sd_users',
					'action' => 'index'
				)); ?></li>
			<?php endif ?>
		</ul>
		<ul class="nav pull-right">
			<?php if ($this->Session->read('Auth.User') != array()): ?>
				<li>
					<?= $this->Html->link(
							__d('SynchroDossier', 'Bonjour %s', $this->Session->read('Auth.User.name')),
							array(
								'plugin' => 'sd_users',
								'controller' => 'sd_users',
								'action' => 'profile',
								$this->Session->read('Auth.User.id')
							)
						);
					 ?>
					</a>
				</li>
				<li>
					<?php echo $this->Html->link(__d('SynchroDossier', "Profil"), array(
						'plugin' => 'sd_users',
						'controller' => 'sd_users',
						'action' => 'profile',
						$this->Session->read('Auth.User.id')
					)); ?>
				</li>
				<li>
					<?php echo $this->Html->link(__d('SynchroDossier', "Déconnexion"), array('plugin' => 'users', 'controller' => 'users', 'action' => 'logout')); ?>
				</li>
			<?php else: ?>
				<li>
					<?php echo $this->Html->link(__d('SynchroDossier', "Connexion"), array('plugin' => 'users', 'controller' => 'users', 'action' => 'login')); ?>
				</li>
			<?php endif ?>
		</ul>
	</div>
</div>