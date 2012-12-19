<?php
Croogo::hookComponent('Users', 'SdUsers.Login');

// Information about Sd roles
$OccitechId = 1;
$SuperAdminRoleId = 4;
$AdminRoleId = 5;
$UtilisateurRoleId = 6;

$rolesInfos = array(
	'Occitech' => array(
		'roleId' => $OccitechId,
		'roleAlias' => 'admin',
		'authorizeRoleCreation' => array($OccitechId, $SuperAdminRoleId, $AdminRoleId, $UtilisateurRoleId)
	),
	'SuperAdmin' => array(
		'roleId' => $SuperAdminRoleId,
		'roleAlias' => 'sdSuperAdmin',
		'authorizeRoleCreation' => array($SuperAdminRoleId, $AdminRoleId, $UtilisateurRoleId)
	),
	'Admin' => array(
		'roleId' => $AdminRoleId,
		'roleAlias' => 'sdAdmin',
		'authorizeRoleCreation' => array($AdminRoleId, $UtilisateurRoleId)
	),
	'Utilisateur' => array(
		'roleId' => $UtilisateurRoleId,
		'roleAlias' => 'sdUtilisateur',
		'authorizeRoleCreation' => array()
	),
);

foreach ($rolesInfos as $roleName => $infos) {
	Configure::write('sd.' . $roleName . '.roleId', $infos['roleId']);
	Configure::write('sd.' . $infos['roleId'] . '.roleAlias', $infos['roleAlias']);
	Configure::write('sd.' . $infos['roleId'] . '.authorizeRoleCreation', $infos['authorizeRoleCreation']);
}

$adminMenu = array(
	'icon' => array('user', 'large'),
	'title' => __('Utilisateurs'),
	'url' => array(
		'plugin' => 'sd_users',
		'controller' => 'sd_users',
		'action' => 'admin_index',
	),
	'children' => array(
		'list' => array(
			'title' => __('Liste'),
			'url' => array(
				'plugin' => 'sd_users',
				'controller' => 'sd_users',
				'action' => 'admin_index',
			),
		),
		'add' => array(
			'title' => __('Ajouter'),
			'url' => array(
				'plugin' => 'sd_users',
				'controller' => 'sd_users',
				'action' => 'admin_add',
			),
		),
	),
);
CroogoNav::add('sdUsers', $adminMenu);
