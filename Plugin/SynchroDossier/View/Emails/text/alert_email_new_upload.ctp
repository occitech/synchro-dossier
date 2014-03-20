<?= __d('synchro_dossier', 'Bonjour %s,', $receiver['Profile']['full_name']) . "\n"; ?>

<?= __d(
	'synchro_dossier',
	'De nouveaux fichiers ont été envoyés par %s dans le dossier %s, le %s à %s :',
	$profile['full_name'],
	$rootFolder,
	date('d/m/Y'),
	date('H\hi')
) . "\n";?>

<?php foreach($files as $file): ?>
   - <?= $file['UploadedFile']['filename']; ?>

<?php endforeach; ?>


--
<?= __d(
	'synchro_dossier',
	'Vous recevez cet email car vous êtes abonné aux alertes sur le dossier \'%s\' du site %s',
	$rootFolder,
	sprintf('%s (%s)', Configure::read('Site.title'), $this->Html->url('/', true))
) ?>