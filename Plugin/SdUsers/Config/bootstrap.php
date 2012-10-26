<?php

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

// Admin menu
CroogoNav::add('sdUsers', array(
	'title' => __('Users'),
	'url' => array(
		'admin' => true,
		'plugin' => 'sd_users',
		'controller' => 'sd_users',
		'action' => 'index',
	),
	'children' => array(
		'list' => array(
			'title' => __('List'),
			'url' => array(
				'admin' => true,
				'plugin' => 'sd_users',
				'controller' => 'sd_users',
				'action' => 'index',
			),
		),
		'add' => array(
			'title' => __('Add'),
			'url' => array(
				'admin' => true,
				'plugin' => 'sd_users',
				'controller' => 'sd_users',
				'action' => 'add',
			),
		),
	),
));
