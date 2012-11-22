<?= __('Bonjour,'); ?>

<?= __('L\'un de vos utilisateurs (dont l\'adresse email est \'%s\')
vient d\'envoyer de nouveaux fichiers sur votre espace de stockage', $user['email']); ?>


<?= __('Voici la liste des fichiers envoyés'); ?>

<?php foreach ($files as $file): ?>
 * <?= __('Nom : '); ?><?= $file['UploadedFile']['filename']; ?> (<?= $this->File->size($file['UploadedFile']['size']); ?>)
<?php endforeach ?>

<?= __('Cordialement'); ?>