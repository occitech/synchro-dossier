<?php
	$loginRoute = array('plugin' => 'users', 'controller' => 'users', 'action' => 'login');
	if ($this->SynchroDossier->routeIsActive($loginRoute)) :
		return;
	endif;
?>
<div class="navbar">
	<div class="navbar-inner">
		<ul class="nav">
			<li><?= $this->Html->link(__d('synchro_dossier', 'Espace client'), '/', array('class' => 'brand')); ?></li>
			<li <?php if($this->SynchroDossier->routeIsActive(array(
				'plugin' => 'uploader',
				'controller' => 'files',
				'action' => 'browse'
			))) {echo 'class="active"';}?>><?= $this->Html->link(__d('synchro_dossier', 'Accueil'), '/'); ?></li>

			<?php if ($this->SdUsers->isAdmin()): ?>
				<li <?php if($this->SynchroDossier->routeIsActive(array(
					'plugin' => 'sd_users',
					'controller' => 'sd_users',
					'action' => 'index'
				))) {echo 'class="active"';}?>><?= $this->Html->link(__d('synchro_dossier', 'Liste des Utilisateurs'), array(
					'plugin' => 'sd_users',
					'controller' => 'sd_users',
					'action' => 'index'
				), array(
					'data-event' => 'ga',
					'data-category' => 'Liste des utilisateurs',
					'data-action' => 'click',
				)); ?></li>
			<?php endif ?>
		</ul>
		<ul class="nav pull-right">
			<?php if ($this->Session->read('Auth.User') != array()): ?>
			<li>
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $this->Session->read('Auth.User.name'); ?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li>
						<?php echo $this->Html->link('<i class="icon-pencil"></i> ' . __d('synchro_dossier', "Profil"),
							array(
								'plugin' => 'sd_users',
								'controller' => 'sd_users',
								'action' => 'profile',
								$this->Session->read('Auth.User.id')
							), array(
								'data-event' => 'ga',
								'data-category' => 'Lien profil',
								'data-action' => 'click',
								'escape' => false,
							)
						); ?>
					</li>
					<li>
						<?php echo $this->Html->link('<i class="icon-envelope"></i> ' . __d('synchro_dossier', "Alertes"),
							array(
								'plugin' => 'sd_users',
								'controller' => 'sd_users',
								'action' => 'alert',
								$this->Session->read('Auth.User.id')
							),
							array(
								'escape' => false
							)
						); ?>
					</li>
					<li>

						<?php echo $this->Html->link('<i class="icon-off"></i> ' . __d('synchro_dossier', "Déconnexion"),
							array(
								'plugin' => 'users',
								'controller' => 'users',
								'action' => 'logout'
							),
							array(
								'escape' => false
							)
						); ?>
					</li>
				</ul>
			</li>
			<?php else : ?>
				<li>
					<?php echo $this->Html->link(__d('synchro_dossier', "Connexion"), $loginRoute); ?>
				</li>
			<?php endif ?>
		</ul>
	</div>
</div>