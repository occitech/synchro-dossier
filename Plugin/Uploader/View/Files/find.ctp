<?php $this->assign('browse_title', __d('uploader', 'Résultats de la recherche')); ?>

<?php
	$this->start('navbar');
		echo $this->element('SynchroDossier.navbar');
	$this->end();
?>

<?php $this->start('sidebar'); ?>
	<?php if (isset($can)): ?>
		<?= $this->element('SynchroDossier.sidebar', array('can' => $can)); ?>
	<?php endif ?>
<?php $this->end();	?>

<?php $this->assign('uploader', ''); ?>

<?= $this->element('Uploader.file_list'); ?>