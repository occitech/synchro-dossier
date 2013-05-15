<?= __d('synchro_dossier', 'Bonjour,'); ?>

<?= __d('synchro_dossier',
	'L\'un de vos utilisateurs (dont l\'adresse email est \'%s\') vient d\'envoyer de nouveaux fichiers sur votre espace de stockage',
	$user['email']
); ?>

<?= __d('synchro_dossier', 'Voici la liste des fichiers envoyés'); ?>

<?php foreach ($files as $file): ?>
 * <?= __d('synchro_dossier', 'Nom : '); ?><?= $file['UploadedFile']['filename']; ?> (<?= $this->File->size($file['UploadedFile']['size']); ?>)
<?php endforeach ?>

<?= __d('synchro_dossier', 'Cordialement'); ?>