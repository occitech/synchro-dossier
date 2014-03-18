<?= __d('synchro_dossier', 'Bonjour,'); ?><?= "\n" ?>

<?= __d('synchro_dossier',
	'L\'un de vos collabotrateurs %s %s vient d\'envoyer de nouveaux fichiers dans le dossier \'%s\' de votre espace de stockage', $profile['firstname'], $profile['name'], $rootFolder
); ?>.<?= "\n" ?>

<?= __d('synchro_dossier', 'Voici la liste des fichiers envoyés'); ?> :<?= "\n" ?>

<?php foreach ($files as $file): ?>
	<?= __d('synchro_dossier', 'Nom : '); ?><?= $file['UploadedFile']['filename']; ?> (<?= $this->File->size($file['UploadedFile']['size']); ?>)<?= "\n" ?>
<?php endforeach ?>

<?= __d('synchro_dossier', 'Cordialement'); ?>
