<div class="users form">
	<h2><?php echo $title_for_layout; ?></h2>
	<?php echo $this->Form->create('User', array('url' => array('plugin' => 'sd_users', 'controller' => 'sd_users', 'action' => 'reset', $email, $key)));?>
		<fieldset>
		<?php
			echo $this->Form->input('password', array('label' => __d('croogo', 'New password')));
			echo $this->Form->input('verify_password', array('type' => 'password', 'label' => __d('croogo', 'Verify Password')));
		?>
		</fieldset>
	<?php echo $this->Form->end(__d('croogo','Submit'));?>
</div>