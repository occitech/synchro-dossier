<?= __d('synchro_dossier', 'Bonjour,'); ?>

<?= __d('synchro_dossier', 'Vous avez été invité sur le dossier %s', $folder['name']) ?>

<?php
	$url = Router::url(array(
		'plugin' => 'uploader',
		'controller' => 'files',
		'action' => 'browse',
		$folder['id']
	), true);
	echo __d('synchro_dossier', 'Vous pouvez accéder au dossier en visitant ce lien : %s', $url);
?>

<?= __d('synchro_dossier', 'Cordialement'); ?>
