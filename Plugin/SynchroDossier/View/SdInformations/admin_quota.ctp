<?php echo $this->Form->create('SdInformation');?>
	<fieldset>
		<div class="tabs">
			<ul>
				<li><a href="#node-main"><span><?php echo __('Users'); ?></span></a></li>
				<?php echo $this->Croogo->adminTabs(); ?>
			</ul>

			<div id="node-main">
			<?=
				$this->Form->hidden('SdInformation.id').
				$this->Form->input('SdInformation.quota', array('label' => __('Quota (Go) : '), 'type' => 'text')) .
				__('Used Quota : ') . $usedQuota;
			?>
			</div>

			<?php echo $this->Croogo->adminTabs(); ?>
			<div class="clear">&nbsp;</div>
		</div>
	</fieldset>
	<div class="buttons">
	<?php
		echo $this->Form->submit(__('Save'), array('label' => __('Save')));
		echo $this->Html->link(__('Cancel'), array(
			'action' => 'index',
		), array(
			'class' => 'cancel',
		));
	?>
	</div>
<?php echo $this->Form->end();?>