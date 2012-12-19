<div class="users form">
	<h2><?php echo __('Login sdfsdfsdsdfsd'); ?></h2>
	<?php echo $this->Form->create('SdUser', array('url' => array('controller' => 'sd_users', 'action' => 'login')));?>
		<fieldset>
		<?php
			echo $this->Form->input('email');
			echo $this->Form->input('password');
		?>
		</fieldset>
	<?php echo $this->Form->end('Submit');?>
</div>