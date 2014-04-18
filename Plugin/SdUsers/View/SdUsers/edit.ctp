<?php
	$this->start('navbar');
		echo $this->element('SynchroDossier.navbar');
	$this->end();
?>
<?= $this->Form->create('SdUser');?>

<?php if(Configure::read('sd.tooltip.quota')) {
$help = '<i class="icon-info-sign"
			data-toggle="popover"
			data-title=""
			data-trigger="' . Configure::read('sd.tooltip.role.trigger') . '"
			data-original-title="' . Configure::read('sd.tooltip.role.title') . '"
			data-content="' . Configure::read('sd.tooltip.role.text') . '"
			data-placement="' . Configure::read('sd.tooltip.role.position') . '"
			data-event="ga"
			data-category="Infobulles"
			data-action="hover"
			data-label="Rôles"
	></i>';
} else {
	$help = '';
}
?>
<div class="row-fluid">
	<div class="span8">

	<?= $this->Html->beginBox(__d('sd_users', 'User Login')); ?>
		<?php if (isset($this->request->data['User']['id'])): ?>
			<?= $this->Form->hidden('User.id'); ?>
		<?php endif ?>
		<?=
			$this->Form->input('User.role_id', array('placeholder' => __d('sd_users', 'Role'), 'label' =>  __d('sd_users', 'Role') . ' ' . $help, 'escape' => false)) .
			$this->Form->input('User.email', array('placeholder' => __d('sd_users', 'Email')))
		?>
		<?php $this->startIfEmpty('password'); ?>
		<?=
			$this->Form->input('User.password', array('required' => false, 'value' => '', 'placeholder' => __d('sd_users', 'Mot de passe')));
		?>
		<?php $this->end(); ?>
		<?= $this->fetch('password'); ?>
	<?= $this->Html->endBox();?>

	<?= $this->Html->beginBox(__d('sd_users', 'Profile'))?>
		<?php if (isset($this->request->data['Profile']['id'])): ?>
			<?= $this->Form->hidden('Profile.id'); ?>
		<?php endif ?>
		<?=
			$this->Form->input('Profile.name', array('placeholder' => __d('sd_users', 'Nom'), 'label' => __d('sd_users', 'Nom'))) .
			$this->Form->input('Profile.firstname', array('placeholder' => __d('sd_users', 'Prénom'), 'label' => __d('sd_users', 'Prénom'))) .
			$this->Form->input('Profile.society', array('placeholder' => __d('sd_users', 'Société'), 'label' => __d('sd_users', 'Société'))) .
			$this->Form->input('Profile.language_id', array(
				'placeholder' => __d('sd_users', 'Language'), 'label' => __d('sd_users', 'Language'),
				'type' => 'select',
			));
		?>
	<?= $this->Html->endBox(); ?>
	</div>

	<div class="span4">
	<?php
		echo $this->Html->beginBox(__d('sd_users', 'Publishing')) .
			$this->Form->button(__d('sd_users', 'Sauver'), array('button' => 'default')) .
			$this->Html->link(__d('sd_users', 'Annuler'), array('action' => 'index'), array(
				'button' => 'danger')
			).
			$this->Html->endBox();

		echo $this->Croogo->adminBoxes();
	?>
	</div>

</div>
<?php echo $this->Form->end(); ?>
