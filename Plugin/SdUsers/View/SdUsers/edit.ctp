<?php
	$this->start('navbar');
		echo $this->element('SynchroDossier.navbar'); 
	$this->end();
?>
<?= $this->Form->create('SdUser');?>

<div class="row-fluid">
	<div class="span8">

	<?= $this->Html->beginBox(__d('SdUsers', 'User Login')); ?>
		<?php if (isset($this->request->data['User']['id'])): ?>
			<?= $this->Form->hidden('User.id'); ?>
		<?php endif ?>
		<?=
			$this->Form->input('User.role_id', array('placeholder' => __d('SdUsers', 'Role'))) .
			$this->Form->input('User.email', array('placeholder' => __d('SdUsers', 'Email'))) .
			$this->Form->input('User.password', array('placeholder' => __d('SdUsers', 'Mot de passe')));
		?>
	<?= $this->Html->endBox();?>

	<?= $this->Html->beginBox(__d('SdUsers', 'Profile'))?>
		<?php if (isset($this->request->data['Profile']['id'])): ?>
			<?= $this->Form->hidden('Profile.id'); ?>
		<?php endif ?>
		<?=
			$this->Form->input('Profile.name', array('placeholder' => __d('SdUsers', 'Nom'), 'label' => __d('SdUsers', 'Nom'))) .
			$this->Form->input('Profile.firstname', array('placeholder' => __d('SdUsers', 'Prénom'), 'label' => __d('SdUsers', 'Prénom'))) .
			$this->Form->input('Profile.society', array('placeholder' => __d('SdUsers', 'Société'), 'label' => __d('SdUsers', 'Société'))) .
			$this->Form->input('Profile.language_id', array(
				'placeholder' => __d('SdUsers', 'Language'), 'label' => __d('SdUsers', 'Language'),
				'type' => 'select',
			));
		?>
	<?= $this->Html->endBox(); ?>
	</div>

	<div class="span4">
	<?php
		echo $this->Html->beginBox(__d('SdUsers', 'Publishing')) .
			$this->Form->button(__d('SdUsers', 'Sauver'), array('button' => 'default')) .
			$this->Html->link(__d('SdUsers', 'Annuler'), array('action' => 'index'), array(
				'button' => 'danger')
			).
			$this->Html->endBox();

		echo $this->Croogo->adminBoxes();
	?>
	</div>

</div>
<?php echo $this->Form->end(); ?>
