<p><?= __d('synchro_dossier', 'Bonjour,'); ?></p>

<p><?= __d('synchro_dossier', 'Vous avez été invité sur le dossier %s', $folder['name']) ?></p>
<p><?php
	$url = Router::url(array(
		'plugin' => 'uploader',
		'controller' => 'files',
		'action' => 'browse',
		$folder['id']
	), true);
	echo __d('synchro_dossier', 'Vous pouvez accéder au dossier en visitant ce lien : %s', $url);
?></p>

<p>
	<?= __d('synchro_dossier', 'Cordialement'); ?><br />
	<?= Configure::read('Site.title') ?>
</p>
