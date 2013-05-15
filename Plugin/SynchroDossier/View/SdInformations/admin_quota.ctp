<?php echo $this->Form->create('SdInformation');?>

<div class="row-fluid">
	<div class="span8">

		<ul class="nav nav-tabs">
			<li><a href="#quota-main" data-toggle="tab"><span><?php echo __d('synchro_dossier', 'Quota'); ?></span></a></li>
			<?php echo $this->Croogo->adminTabs(); ?>
		</ul>

		<div class="tab-content">
			<div id="quota-main" class="tab-pane">
			<?=
				$this->Form->hidden('SdInformation.id').
				$this->Form->input('SdInformation.quota_mb', array('label' => __d('synchro_dossier', 'Quota (Mo) : '), 'type' => 'text')) .
				__d('synchro_dossier', 'Quota utilisé : ') . $usedQuota . ' M';
			?>
			</div>

			<?php echo $this->Croogo->adminTabs(); ?>
		</div>
	</div>
	<div class="span4">
	<?php
		echo $this->Html->beginBox(__d('synchro_dossier', 'Publishing')) .
			$this->Form->button(__d('synchro_dossier', 'Sauver'), array('button' => 'default')) .
			$this->Html->link(__d('synchro_dossier', 'Annuler'), array('action' => 'index'), array(
				'button' => 'danger')
			).
			$this->Html->endBox();

		echo $this->Croogo->adminBoxes();
	?>
	</div>

</div>
<?php echo $this->Form->end();?>