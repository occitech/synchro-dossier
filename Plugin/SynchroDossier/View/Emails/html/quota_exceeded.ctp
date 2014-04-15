<p><?= __d('synchro_dossier', 'Bonjour,'); ?></p>

<p><?= __d('synchro_dossier', 'L\'un de vos utilisateurs (dont l\'adresse email est \'%s\')
a essayé d\'envoyer un fichier dont la taille dépasse le quota restant dans votre
espace de stockage.', $user['email']); ?></p>


<p><?= __d('synchro_dossier', 'Voici les informations du fichier que l\'utilisateur a essayé d\'envoyer :'); ?></p>

<ul>
	<li><?= __d('synchro_dossier', 'Nom : '); ?><?= $data['file']['name']; ?></li>

	<li><?= __d('synchro_dossier', 'Taille : '); ?> <?= $this->File->size($data['file']['size']); ?></li>
</ul>

<p><?= __d('synchro_dossier', 'Pour que vos utilisateurs puissent de nouveau envoyer des fichiers vous devez augmenter votre quota ou supprimer des fichiers.'); ?></p>


<p><?= __d('synchro_dossier', 'Cordialement'); ?></p>
