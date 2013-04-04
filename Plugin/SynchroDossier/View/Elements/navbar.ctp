<div class="navbar">
	<div class="navbar-inner">
		<ul class="nav">
			<li><?= $this->Html->link(__('Espace client'), '/', array('class' => 'brand')); ?></li>
			<li class="active"><?= $this->Html->link(__('Accueil'), '/'); ?></li>
		</ul>
		<ul class="nav pull-right">
			<?php if ($this->Session->read('Auth.User') != array()): ?>
				<li>
					<a href="#">
						<?= __('Bonjour ') . $this->Session->read('Auth.User.username'); ?>
					</a>
				</li>
				<li>
					<?php echo $this->Html->link(__("Profil"), array('plugin' => 'sd_users', 'controller' => 'sd_users', 'action' => 'edit', $this->Session->read('Auth.User.id'))); ?>
				</li>
				<li>
					<?php echo $this->Html->link(__("Déconnexion"), array('plugin' => 'users', 'controller' => 'users', 'action' => 'logout')); ?>
				</li>
			<?php else: ?>
				<li>
					<?php echo $this->Html->link(__("Connexion"), array('plugin' => 'users', 'controller' => 'users', 'action' => 'login')); ?>
				</li>
			<?php endif ?>
		</ul>
	</div>
</div>