<?= __d('synchro_dossier', 'Bonjour %s,', $receiver['Profile']['full_name']) . "\n"; ?>

<?= __d(
	'synchro_dossier',
	'De nouveaux fichiers ont été envoyés par %s dans le dossier %s :',
	$profile['full_name'],
	$rootFolder
) . "\n";?>

<?php foreach($files as $file): ?>
   - <?= __d(
		'synchro_dossier',
		'%s: ajouté le %s',
		$file['UploadedFile']['filename'],
		date('d/m/Y H:i', strtotime($file['UploadedFile']['created']))
	);?>

<?php endforeach; ?>
