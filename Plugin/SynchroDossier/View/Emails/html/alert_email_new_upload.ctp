<?= __('Bonjour,'); ?><br><br>

<?= __('L\'un de vos utilisateurs (dont l\'adresse email est \'%s\')
vient d\'envoyer de nouveaux fichiers sur votre espace de stockage', $user['email']); ?><br><br>

<?= __('Voici la liste des fichiers envoyés'); ?><br><br>

<?php foreach ($files as $file): ?>
 * <?= __('Nom : '); ?><?= $file['UploadedFile']['filename']; ?> (<?= $this->File->size($file['UploadedFile']['size']); ?>) <br>
<?php endforeach ?><br>

<?= __('Cordialement'); ?>