<?php $this->extend('SdUsers/edit'); ?>
<?php $this->startIfEmpty('password'); ?>
	<?=
		$this->Form->input('User.password', array('placeholder' => __d('sd_users', 'Mot de passe'), 'label' =>  __d('sd_users', 'Mot de passe')));
	?>
<?php $this->end(); ?>
