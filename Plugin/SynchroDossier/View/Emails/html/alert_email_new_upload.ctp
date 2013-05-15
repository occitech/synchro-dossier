<?= __d('synchro_dossier', 'Bonjour,'); ?><br><br>

<?= __d('synchro_dossier',
	'L\'un de vos utilisateurs (dont l\'adresse email est \'%s\') vient d\'envoyer de nouveaux fichiers sur votre espace de stockage',
	$user['email']
); ?><br><br>

<?= __d('synchro_dossier', 'Voici la liste des fichiers envoyés'); ?><br><br>

<?php foreach ($files as $file): ?>
 * <?= __d('synchro_dossier', 'Nom : '); ?><?= $file['UploadedFile']['filename']; ?> (<?= $this->File->size($file['UploadedFile']['size']); ?>) <br>
<?php endforeach ?><br>

<?= __d('synchro_dossier', 'Cordialement'); ?>