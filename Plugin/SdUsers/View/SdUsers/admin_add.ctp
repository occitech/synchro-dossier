<?php $this->extend('/Common/admin_edit'); ?>
<?php echo $this->Form->create('User');?>
	<fieldset>
		<div class="tabs">
			<ul>
				<li><a href="#node-main"><span><?php echo __('Users'); ?></span></a></li>
				<li><a href="#node-profile"><span><?php echo __('Profile'); ?></span></a></li>
				<?php echo $this->Croogo->adminTabs(); ?>
			</ul>

			<div id="node-main">
			<?=
				$this->Form->input('User.role_id', array('label' => __('Role'))).
				$this->Form->input('User.username', array('label' => __('Username'))).
				$this->Form->input('User.email', array('label' => __('Email'))).
				$this->Form->input('User.password', array('label' => __('Password'))).
				$this->Form->input('User.status', array('label' => __('Status')));
			?>
			</div>

			<div id="node-profile">
			<?=
				$this->Form->input('Profile.name', array('label' => __('Name'))) .
				$this->Form->input('Profile.firstname', array('label' => __('Firstname'))) .
				$this->Form->input('Profile.society', array('label' => __('Society')));
			?>
			</div>
			<?php echo $this->Croogo->adminTabs(); ?>
			<div class="clear">&nbsp;</div>
		</div>
	</fieldset>
	<div class="buttons">
	<?php
		echo $this->Form->submit(__('Apply'), array('name' => 'apply', 'label' => __('Apply')));
		echo $this->Form->submit(__('Save'), array('label' => __('Save')));
		echo $this->Html->link(__('Cancel'), array(
			'action' => 'index',
		), array(
			'class' => 'cancel',
		));
	?>
	</div>
<?php echo $this->Form->end();?>