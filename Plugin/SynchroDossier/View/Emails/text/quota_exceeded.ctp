<?= __d('synchro_dossier', 'Bonjour,'); ?>

<?= __d('synchro_dossier', 'L\'un de vos utilisateurs (dont l\'adresse email est \'%s\')
a essayé d\'envoyer un fichier dont la taille dépasse le quota restant dans votre
espace de stockage.', $user['email']); ?>


<?= __d('synchro_dossier', 'Voici les informations du fichier que l\'utilisateur a essayé d\'envoyer :'); ?>

 * <?= __d('synchro_dossier', 'Nom : '); ?><?= $data['file']['name']; ?>

 * <?= __d('synchro_dossier', 'Taille : '); ?> <?= $this->File->size($data['file']['size']); ?>


<?= __d('synchro_dossier', 'Pour que vos utilisateurs puissent de nouveau envoyer des fichiers vous devez augmenter votre quota ou supprimer des fichiers.'); ?>


<?= __d('synchro_dossier', 'Cordialement'); ?>
