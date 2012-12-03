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
				<?=
					$this->Form->input('User.role_id', array('placeholder' => __('Role'))).
					$this->Form->input('User.username', array('placeholder' => __('Username'))).
					$this->Form->input('User.email', array('placeholder' => __('Email'))).
					$this->Form->input('User.password', array('placeholder' => __('Password'))).
					$this->Form->input('User.status', array('label' => __('Status')));
				?>
			</div>

			<div id="user-profile" class="tab-pane">
				<?=
					$this->Form->input('Profile.name', array('placeholder' => __('Name'))) .
					$this->Form->input('Profile.firstname', array('placeholder' => __('Firstname'))) .
					$this->Form->input('Profile.society', array('placeholder' => __('Society')));
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
