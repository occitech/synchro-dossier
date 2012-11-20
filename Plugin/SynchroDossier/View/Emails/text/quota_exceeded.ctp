Bonjour,

L'un de vos utilisateurs (dont l'adresse email est '<?= $user['email']; ?>')
a essayé d'envoyer un fichier dont la taille dépasse le quota restant dans votre
espace de stockage.


Voici les informations du fichier que l'utilisateur a essayé d'envoyer :

 * Nom : <?= $data['file']['name']; ?>

 * Taille : <?= $this->File->size($data['file']['size']); ?>


Pour que vos utilisateurs puissent de nouveau envoyer des fichiers vous devez
augmenter votre quota ou supprimer des fichiers.


Cordialement