<?php echo $this->Form->create('SdInformation');?>

<div class="row-fluid">
	<div class="span8">

		<ul class="nav nav-tabs">
			<li><a href="#quota-main" data-toggle="tab"><span><?php echo __d('SynchroDossier', 'Quota'); ?></span></a></li>
			<?php echo $this->Croogo->adminTabs(); ?>
		</ul>

		<div class="tab-content">
			<div id="quota-main" class="tab-pane">
			<?=
				$this->Form->hidden('SdInformation.id').
				$this->Form->input('SdInformation.quota_mb', array('label' => __d('SynchroDossier', 'Quota (Mo) : '), 'type' => 'text')) .
				__d('SynchroDossier', 'Quota utilisé : ') . $usedQuota . ' M';
			?>
			</div>

			<?php echo $this->Croogo->adminTabs(); ?>
		</div>
	</div>
	<div class="span4">
	<?php
		echo $this->Html->beginBox(__d('SynchroDossier', 'Publishing')) .
			$this->Form->button(__d('SynchroDossier', 'Sauver'), array('button' => 'default')) .
			$this->Html->link(__d('SynchroDossier', 'Annuler'), array('action' => 'index'), array(
				'button' => 'danger')
			).
			$this->Html->endBox();

		echo $this->Croogo->adminBoxes();
	?>
	</div>

</div>
<?php echo $this->Form->end();?>