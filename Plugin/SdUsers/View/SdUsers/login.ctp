<div class="users form">
	<h2><?php echo __('Connexion utilisateur'); ?></h2>
	<?php echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'login')));?>
		<fieldset>
		<?php
			echo $this->Form->input('email', array('label' =>__('Adresse email')));
			echo $this->Form->input('password', array('label' => __('Mot de passe')));
		?>
		</fieldset>
	<?php echo $this->Form->end('Submit');?>
</div>