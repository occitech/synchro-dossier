<?php $this->extend('/Common/admin_edit'); ?>
<?php echo $this->Form->create('SdUser');?>

<div class="row-fluid">
	<div class="span8">

		<ul class="nav nav-tabs">
			<li><a href="#user-main" data-toggle="tab"><span><?php echo __('Users'); ?></span></a></li>
			<li><a href="#user-profile" data-toggle="tab"><span><?php echo __('Profile'); ?></span></a></li>
			<?php echo $this->Croogo->adminTabs(); ?>
		</ul>

		<div class="tab-content">
			<div id="user-main" class="tab-pane">
				<?php if (isset($this->request->data['User']['id'])): ?>
					<?= $this->Form->hidden('User.id'); ?>
				<?php endif ?>
				<?=
					$this->Form->input('User.role_id', array('placeholder' => __('Role'))) .
					$this->Form->input('User.email', array('placeholder' => __('Email'))) .
					$this->Form->input('User.password', array('placeholder' => __('Mot de passe'), 'type' => 'text'));
				?>
			</div>

			<div id="user-profile" class="tab-pane">
				<?php if (isset($this->request->data['Profile']['id'])): ?>
					<?= $this->Form->hidden('Profile.id'); ?>
				<?php endif ?>
				<?=
					$this->Form->input('Profile.name', array('placeholder' => __('Nom'), 'label' => __('Nom'))) .
					$this->Form->input('Profile.firstname', array('placeholder' => __('Prénom'), 'label' => __('Prénom'))) .
					$this->Form->input('Profile.society', array('placeholder' => __('Société'), 'label' => __('Société')));
				?>
			</div>

			<?php echo $this->Croogo->adminTabs(); ?>
		</div>
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
