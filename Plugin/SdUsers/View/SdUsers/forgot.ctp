<div class="users form">
	<h2><?php echo $title_for_layout; ?></h2>
	<?php echo $this->Form->create('User', array('url' => array('plugin' => 'sd_users', 'controller' => 'sd_users', 'action' => 'forgot')));?>
		<fieldset>
		<?php
			echo $this->Form->input('email', array('label' => __d('croogo', 'Email')));
		?>
		</fieldset>
	<?php echo $this->Form->end(__d('croogo', 'Submit'));?>
</div>