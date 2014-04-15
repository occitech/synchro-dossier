<div id="renameFileModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?= __d('synchro_dossier', 'Renommer le fichier'); ?></h3>
	</div>
	<?php echo $this->element('SynchroDossier.renameForm', array('folderId' => $folderId)); ?>
</div>
