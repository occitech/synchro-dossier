<div class="navbar">
	<div class="navbar-inner">
		<a class="brand" href="#"><?= __('Espace client'); ?></a>
		<ul class="nav">
			<li class="active"><?= $this->Html->link(__('Accueil'), '/'); ?></li>
			<li><?= $this->Html->link(__('Aide en ligne'), '#'); ?></li>
		</ul>
		<ul class="nav pull-right">
			<li>
				<a href="#">
					<?= __('Bonjour ') . $this->Session->read('Auth.User.username'); ?>
				</a>
			</li>
			<li>
				<?php echo $this->Html->link(__("Log out"), array('plugin' => 'users', 'controller' => 'users', 'action' => 'logout')); ?>
			</li>
		</ul>
	</div>
</div>