<div class="users form">
	<h2><?php echo __d('SdUsers', 'Connexion utilisateur'); ?></h2>
	<?php echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'login')));?>
		<fieldset>
		<?php
			echo $this->Form->input('email', array('label' =>__d('SdUsers', 'Adresse email')));
			echo $this->Form->input('password', array('label' => __d('SdUsers', 'Mot de passe')));
		?>
		</fieldset>
		<?php echo $this->Form->end(array(
			'label' => __d('SdUsers', 'Se connecter'),
			'class' => 'btn',
			'div' => array('class' => 'actions'),
			'after' =>
				 $this->Html->link(
					__d('SdUsers', 'Mot de passe oublié ?'),
					array('plugin' => 'users', 'controller' => 'users', 'action' => 'forgot'),
					array('class' => 'btn-small')
				)
		));?>
</div>