<?= __d('SynchroDossier', 'Bonjour,'); ?><br><br>

<?= __d('SynchroDossier', 
	'L\'un de vos utilisateurs (dont l\'adresse email est \'%s\') vient d\'envoyer de nouveaux fichiers sur votre espace de stockage',
	$user['email']
); ?><br><br>

<?= __d('SynchroDossier', 'Voici la liste des fichiers envoyés'); ?><br><br>

<?php foreach ($files as $file): ?>
 * <?= __d('SynchroDossier', 'Nom : '); ?><?= $file['UploadedFile']['filename']; ?> (<?= $this->File->size($file['UploadedFile']['size']); ?>) <br>
<?php endforeach ?><br>

<?= __d('SynchroDossier', 'Cordialement'); ?>