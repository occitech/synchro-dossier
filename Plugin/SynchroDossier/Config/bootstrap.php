<?php
Croogo::hookComponent('*', 'SynchroDossier.SynchroDossier');

$authConfig = array(
	'all' => array(
		'userModel' => 'Users.User',
		'fields' => array(
			'username' => 'email',
			'password' => 'password',
		),
		'scope' => array(
			'User.status' => 1,
		),
	),
	'Form'
);

Configure::write('Acl.Auth.authenticate', $authConfig);
Configure::write('Acl.Auth.loginRedirect', array(
	'plugin' => 'uploader',
	'controller' => 'files',
	'action' => 'browse',
));

$adminMenu = array(
	'icon' => array('file', 'large'),
	'title' => __d('synchro_dossier', 'SynchroDossier'),
	'url' => array(
		'admin' => true,
		'plugin' => 'synchro_dossier',
		'controller' => 'sd_informations',
		'action' => 'index',
	),
	'children' => array(
		'list' => array(
			'title' => __d('synchro_dossier', 'Quota'),
			'url' => array(
				'admin' => true,
				'plugin' => 'synchro_dossier',
				'controller' => 'sd_informations',
				'action' => 'quota',
			),
		),
	),
);

CroogoNav::add('synchro', $adminMenu);

Configure::write('sd.mail.quotaExceeded.subject', 'Synchro-Dossier - Quota dépassé');
Configure::write('sd.mail.quotaExceeded.from', array('admin@synchro-dossier.fr' => 'Synchro Dossier'));
Configure::write('sd.mail.alertEmailNewUpload.subject', 'Synchro-Dossier - Nouveaux fichiers envoyés');
Configure::write('sd.mail.alertEmailNewUpload.from', array('admin@synchro-dossier.fr' => 'Synchro Dossier'));

Configure::write('sd.config.useSsl', false);
Configure::write('sd.config.useModeBox', false);
Configure::write('sd.config.maxDownloadableZipSize', 1024*1024*50);

Configure::write('Asset.timestamp', 'force');

CakePlugin::load('DbMigration');

Configure::write('sd.tooltip', array(
	'quota' => array(
		'trigger' => 'hover',
		'position' => 'right',
		'title' => __d('synchro_dossier', 'Qu\'est-ce que le quota ?'),
		'text' => __d('synchro_dossier', 'Chaque fichier envoyé occupe de l\'espace sur le disque.<br/><br/>
		Voici un état de la place occupée et disponible.<br/><br/>
		Lorsque votre espace est plein, vous pouvez supprimer des fichiers ou commander de l\'espace supplémentaire.
		(rappel : le quota n\'est pas affiché aux utilisateurs)'
		)
	),
	'role' => array(
		'trigger' => 'hover',
		'position' => 'left',
		'title' => __d('synchro_dossier', 'Quels sont les différents rôles ?'),
		'text' => __d('synchro_dossier', '
			<strong><em>Utilisateur :&nbsp;</em></strong>peut uniquement entrer dans les dossiers sur lesquels il est invité par un administrateur ou super-administrateur.<br/><br/>
			<strong><em>Administrateur :&nbsp;</em></strong>peut créer des dossiers et créer des utilisateurs sur ses propres dossiers.<br/><br/>
			<strong><em>Super administrateur :&nbsp;</em></strong>peut voir tous les dossiers, créer des dossiers, créer des administrateurs et des utilisateurs, et commander de l\'espace supplémentaire si nécessaire.<br/><br/>'
		)
	),
	'rights' => array(
		'trigger' => 'hover',
		'position' => 'bottom',
		'title' => __d('synchro_dossier', 'Quels sont les différents droits ?'),
		'text' => __d('synchro_dossier', '
			<strong><em>Aucun droit : &nbsp;</em></strong>Ne peut ni voir ni consulter ce dossier.<br/><br/>
			<strong><em>Lecture : &nbsp;</em></strong>Peut voir et consulter le contenu du dossier.<br/><br/>
			<strong><em>Renommage : &nbsp;</em></strong>Permet de renommer les fichiers et dossiers.<br/><br/>
			<strong><em>Création / Envoi : &nbsp;</em></strong>Peut envoyer des fichiers dans la boite d\'envoi et créer des répertoires.<br/><br/>
			<strong><em>Suppression : &nbsp;</em></strong>Peut supprimer des fichiers de la boite d\'envoi.<br/><br/>
			<strong><em>Changer les droits : &nbsp;</em></strong>Peut changer les droits des utilisateurs de la boite d\'envoi.<br/><br/>'
		)
	),
	'email_alert' => array(
		'trigger' => 'hover',
		'position' => 'top',
		'title' => __d('synchro_dossier', 'Qu\'est-ce qu\'une alerte email ?'),
		'text' => __d('synchro_dossier', '<strong><em>Alerte Email : &nbsp;</em></strong> Vous envoie un E-mail d\'alerte automatique et instantané dès qu\'un nouveau fichier est envoyé par un autre utilisateur dans ce dossier.')
	)
));
