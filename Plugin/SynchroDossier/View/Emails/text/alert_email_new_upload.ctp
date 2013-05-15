<?= __d('SynchroDossier', 'Bonjour,'); ?>

<?= __d('SynchroDossier', 
	'L\'un de vos utilisateurs (dont l\'adresse email est \'%s\') vient d\'envoyer de nouveaux fichiers sur votre espace de stockage',
	$user['email']
); ?>

<?= __d('SynchroDossier', 'Voici la liste des fichiers envoyés'); ?>

<?php foreach ($files as $file): ?>
 * <?= __d('SynchroDossier', 'Nom : '); ?><?= $file['UploadedFile']['filename']; ?> (<?= $this->File->size($file['UploadedFile']['size']); ?>)
<?php endforeach ?>

<?= __d('SynchroDossier', 'Cordialement'); ?>