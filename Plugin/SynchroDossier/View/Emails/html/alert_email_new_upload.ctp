<p><?= __d('synchro_dossier', 'Bonjour %s,', $receiver['Profile']['full_name']); ?></p>

<p><?= __d(
	'synchro_dossier',
	'De nouveaux fichiers ont été envoyés par %s dans le dossier %s :',
	$profile['full_name'],
	$rootFolder
);?></p>

<ul>
	<?php foreach($files as $file): ?>
		<li><?= $file['UploadedFile']['filename']; ?></li>
	<?php endforeach; ?>
</ul>

<p>
	--<br />
	<?= __d(
		'synchro_dossier',
		'Vous recevez cet email car vous êtes abonné aux alertes sur le dossier \'%s\' du site %s',
		$rootFolder,
		$this->Html->link(Configure::read('Site.title'), $this->Html->url('/', true))
	) ?>
</p>