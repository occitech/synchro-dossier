<?= __d('synchro_dossier', 'Bonjour,'); ?><br><br>

<?= __d('synchro_dossier',
	'L\'un de vos collaborateurs, %s %s vient d\'envoyer de nouveaux fichiers dans le dossier \'%s\' de votre espace de stockage', $profile['firstname'], $profile['name'], $rootFolder
); ?>.<br><br>

<?= __d('synchro_dossier', 'Voici la liste des fichiers envoyés'); ?> :<br><br>

<?php foreach ($files as $file): ?>
	<?= __d('synchro_dossier', 'Nom : '); ?><?= $file['UploadedFile']['filename']; ?> (<?= $this->File->size($file['UploadedFile']['size']); ?>) <br>
<?php endforeach ?><br>

<?= __d('synchro_dossier', 'Cordialement'); ?>
