<?= $this->Plupload->loadAsset('jquery'); ?>
<?php
	$this->start('navbar');
		echo $this->element('SynchroDossier.navbar');
	$this->end();
?>
<?php
	$this->start('uploader');
		if (isset($folderId) && !is_null($folderId) && $this->UploaderAcl->userCan($folderAco['Aco'], 'create')) {
			echo $this->element('Uploader.plupload_widget');
		}
	$this->end();
?>
<?php $this->start('sidebar'); ?>
	<?php if (isset($can)): ?>
		<?= $this->element('SynchroDossier.sidebar', array('can' => $can)); ?>
	<?php endif ?>
<?php $this->end();	?>

<?= $this->element('Uploader.file_list'); ?>