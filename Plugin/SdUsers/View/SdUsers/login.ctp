<div class="users form">
	<h2><?php echo __d('sd_users', 'Connexion utilisateur'); ?></h2>
	<?php echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'login')));?>
		<fieldset>
		<?php
			echo $this->Form->input('email', array('label' =>__d('sd_users', 'Adresse email')));
			echo $this->Form->input('password', array('label' => __d('sd_users', 'Mot de passe')));
		?>
		</fieldset>
		<?php echo $this->Form->end(array(
			'label' => __d('sd_users', 'Se connecter'),
			'class' => 'btn',
			'div' => array('class' => 'actions'),
			'after' =>
				 $this->Html->link(
					__d('sd_users', 'Mot de passe oublié ?'),
					array('plugin' => 'sd_users', 'controller' => 'sd_users', 'action' => 'forgot'),
					array('class' => 'btn-small')
				)
		));?>
</div>