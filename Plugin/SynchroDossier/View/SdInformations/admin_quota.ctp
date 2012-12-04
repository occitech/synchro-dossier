<?php echo $this->Form->create('SdInformation');?>

<div class="row-fluid">
	<div class="span8">

		<ul class="nav nav-tabs">
			<li><a href="#quota-main" data-toggle="tab"><span><?php echo __('Quota'); ?></span></a></li>
			<?php echo $this->Croogo->adminTabs(); ?>
		</ul>

		<div class="tab-content">
			<div id="quota-main" class="tab-pane">
			<?=
				$this->Form->hidden('SdInformation.id').
				$this->Form->input('SdInformation.quota_mb', array('label' => __('Quota (Mo) : '), 'type' => 'text')) .
				__('Quota utilisé : ') . $usedQuota . ' M';
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
<?php echo $this->Form->end();?>