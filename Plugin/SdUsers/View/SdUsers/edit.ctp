<?php $this->extend('/Common/admin_edit'); ?>
<?= $this->Form->create('SdUser');?>

<div class="row-fluid">
	<div class="span8">

	<?= $this->Html->beginBox(__('User Login')); ?>
		<?php if (isset($this->request->data['User']['id'])): ?>
				<?= $this->Form->hidden('User.id'); ?>
		<?php endif ?>
		<?=
			$this->Form->input('User.role_id', array('placeholder' => __('Role'))) .
			$this->Form->input('User.email', array('placeholder' => __('Email'))) .
			$this->Form->input('User.password', array('placeholder' => __('Mot de passe'), 'type' => 'text'));
		?>
	<?= $this->Html->endBox();?>

	<?= $this->Html->beginBox(__('Profile'))?>
		<?php if (isset($this->request->data['Profile']['id'])): ?>
			<?= $this->Form->hidden('Profile.id'); ?>
		<?php endif ?>
		<?=
			$this->Form->input('Profile.name', array('placeholder' => __('Nom'), 'label' => __('Nom'))) .
			$this->Form->input('Profile.firstname', array('placeholder' => __('Prénom'), 'label' => __('Prénom'))) .
			$this->Form->input('Profile.society', array('placeholder' => __('Société'), 'label' => __('Société')));
		?>
	<?= $this->Html->endBox(); ?>

		<?php echo $this->Croogo->adminTabs(); ?>
	</div>

	<div class="span4">
	<?php
		echo $this->Html->beginBox(__('Publishing')) .
			$this->Form->button(__('Sauver'), array('button' => 'default')) .
			$this->Html->link(__('Annuler'), array('action' => 'index'), array(
				'button' => 'danger')
			).
			$this->Html->endBox();

		echo $this->Croogo->adminBoxes();
	?>
	</div>

</div>
<?php echo $this->Form->end(); ?>
