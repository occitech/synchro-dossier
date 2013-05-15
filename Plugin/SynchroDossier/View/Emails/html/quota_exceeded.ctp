<?= __d('SynchroDossier', 'Bonjour,'); ?>

<?= __d('SynchroDossier', 'L\'un de vos utilisateurs (dont l\'adresse email est \'%s\')
a essayé d\'envoyer un fichier dont la taille dépasse le quota restant dans votre
espace de stockage.', $user['email']); ?>


<?= __d('SynchroDossier', 'Voici les informations du fichier que l\'utilisateur a essayé d\'envoyer :'); ?>

 * <?= __d('SynchroDossier', 'Nom : '); ?><?= $data['file']['name']; ?>

 * <?= __d('SynchroDossier', 'Taille : '); ?> <?= $this->File->size($data['file']['size']); ?>


<?= __d('SynchroDossier', 'Pour que vos utilisateurs puissent de nouveau envoyer des fichiers vous devez
augmenter votre quota ou supprimer des fichiers.'); ?>


<?= __d('SynchroDossier', 'Cordialement'); ?>