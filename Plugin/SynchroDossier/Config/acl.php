<?php

$ROLE_SUPER_ADMIN = 'sdSuperAdmin';
$ROLE_SD_ADMIN = 'sdAdmin';
$ROLE_SD_USER = 'sdUtilisateur';
$ROLE_PUBLIC = 'public';

$EVERYONE = array(
	$ROLE_SUPER_ADMIN, $ROLE_SD_USER, $ROLE_SD_ADMIN
);
$ADMINISTRATORS = array(
	$ROLE_SUPER_ADMIN, $ROLE_SD_ADMIN
);

$uploaderAcls = array(
	'Uploader/Comments/admin_index' => $ADMINISTRATORS,
	'Uploader/Comments' => $EVERYONE,
	'Uploader/Comments/admin_edit' => $EVERYONE,
	'Uploader/Comments/admin_delete' => $EVERYONE,
	'Uploader/Comments/admin_process' => $EVERYONE,
	'Uploader/Comments/index' => $EVERYONE,
	'Uploader/Comments/add' => $EVERYONE,
	'Uploader/Comments/delete' => $EVERYONE,

	'Uploader/Files/createSharing' => $ADMINISTRATORS,
	'Uploader/Files/rights' => $ADMINISTRATORS,
	'Uploader/Files/toggleRight' => $ADMINISTRATORS,
	'Uploader/Files/removeRight' => $ADMINISTRATORS,
	'Uploader/Files/deleteFile' => $EVERYONE,
	'Uploader/Files/deleteFolder' => $ADMINISTRATORS,

	'Uploader/Files/find' => $EVERYONE,
	'Uploader/Files/allFilesUploadedInBatch' => $EVERYONE,
	'Uploader/Files/preview' => $EVERYONE,
	'Uploader/Files/browse' => $EVERYONE,
	'Uploader/Files/upload' => $EVERYONE,
	'Uploader/Files/download' => $EVERYONE,
	'Uploader/Files/rename' => $EVERYONE,
	'Uploader/Files/createFolder' => $EVERYONE,
	'Uploader/Files/downloadZipFolder' => $EVERYONE,
	'Uploader/Files/addTags' => $EVERYONE,
);

$usersAcl = array(
	'SdUsers/SdUsers/add' => $ADMINISTRATORS,
	'SdUsers/SdUsers/delete' => $ADMINISTRATORS,

	'SdUsers/SdUsers/index' => $EVERYONE,
	'SdUsers/SdUsers/edit' => $EVERYONE,
	'SdUsers/SdUsers/profile' => $EVERYONE,
	'SdUsers/SdUsers/manageAlertEmail' => $EVERYONE,
	'SdUsers/SdUsers/changeUserPassword' => $EVERYONE,

	'SdUsers/SdUsers/forgot' => array($ROLE_PUBLIC),
	'SdUsers/SdUsers/reset' => array($ROLE_PUBLIC),

	'Users/Users/logout' => $EVERYONE,
	'Users/Users/login' => $EVERYONE,
	'Users/Users/index' => $EVERYONE,
);

$otherAcls = array(
	'Acl/AclPermissions/admin_upgrade' => $EVERYONE,
	'Blocks/Blocks/admin_toggle' => $EVERYONE,
	'FileStorage' => $EVERYONE,
	'Menus/Links/admin_toggle' => $EVERYONE,
	'Nodes/Nodes/admin_toggle' => $EVERYONE,
	'Plupload' => $EVERYONE,
	'Plupload/Plupload' => $EVERYONE,
	'SdLogs' => $EVERYONE,
	'SynchroDossier/SdInformations/admin_quota' => array()
);

$config = array_merge(
	$uploaderAcls,
	$usersAcl,
	$otherAcls
);
