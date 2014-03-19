<p><?= __d('synchro_dossier', 'Bonjour %s,', $receiver['Profile']['full_name']); ?></p>

<p><?= __d(
	'synchro_dossier',
	'De nouveaux fichiers ont été envoyés par %s dans le dossier %s :',
	$profile['full_name'],
	$rootFolder
);?></p>

<ul>
	<?php foreach($files as $file): ?>
		<li><?= __d(
			'synchro_dossier',
			'%s: ajouté le %s',
			$file['UploadedFile']['filename'],
			date('d/m/Y H:i', strtotime($file['UploadedFile']['created']))
		);?></li>
	<?php endforeach; ?>
</ul>
