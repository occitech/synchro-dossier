<?= __('Bonjour,'); ?>

<?= __('L\'un de vos utilisateurs (dont l\'adresse email est \'%s\')
a essayé d\'envoyer un fichier dont la taille dépasse le quota restant dans votre
espace de stockage.', $user['email']); ?>


<?= __('Voici les informations du fichier que l\'utilisateur a essayé d\'envoyer :'); ?>

 * <?= __('Nom : '); ?><?= $data['file']['name']; ?>

 * <?= __('Taille : '); ?> <?= $this->File->size($data['file']['size']); ?>


<?= __('Pour que vos utilisateurs puissent de nouveau envoyer des fichiers vous devez
augmenter votre quota ou supprimer des fichiers.'); ?>


<?= __('Cordialement'); ?>