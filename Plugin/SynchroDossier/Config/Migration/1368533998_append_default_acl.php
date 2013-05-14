<?php
class AppendDefaultAcl extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	const ROLE_SD_SUPER_ADMIN = 'sdSuperAdmin';
	const ROLE_SD_ADMIN       = 'sdAdmin';
	const ROLE_SD_USER        = 'sdUtilisateur';

	public $description = 'add predefined Aco for actions and roles';
	public $RolesAction = array(
		'Acl/AclPermissions/admin_upgrade' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Blocks/Blocks/admin_toggle' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'FileStorage' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Menus/Links/admin_toggle' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Nodes/Nodes/admin_toggle' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Plupload' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Plupload/Plupload' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Plupload/Plupload/upload' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Plupload/Plupload/widget' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'SdLogs' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'SdUsers/SdUsers/index' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'SdUsers/SdUsers/add' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'SdUsers/SdUsers/edit' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'SdUsers/SdUsers/delete' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_ADMIN
		),
		'SdUsers/SdUsers/profile' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'SdUsers/SdUsers/manageAlertEmail' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'SdUsers/SdUsers/changeUserPassword' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),

		'SdUsers/SdUsers/index' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'SdUsers/SdUsers/edit' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'SdUsers/SdUsers/add' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'SdUsers/SdUsers/delete' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Users/Users/logout' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Users/Users/login' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Comments' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Comments/admin_index' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_ADMIN
		),
		'Uploader/Comments/admin_edit' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Comments/admin_delete' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Comments/admin_process' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Comments/index' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Comments/add' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Comments/delete' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Files/find' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Files/allFilesUploadedInBatch' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Files/preview' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Files/delete' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),

		'Uploader/Files/createSharing' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_ADMIN
		),
		'Uploader/Files/rights' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_ADMIN
		),
		'Uploader/Files/toggleRight' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_ADMIN
		),
		'Uploader/Files/removeRight' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_ADMIN
		),
		'Uploader/Files/browse' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Files/upload' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Files/download' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Files/rename' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Files/createFolder' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'Uploader/Files/downloadZipFolder' => array(
			self::ROLE_SD_SUPER_ADMIN, self::ROLE_SD_USER, self::ROLE_SD_ADMIN
		),
		'SynchroDossier/SdInformations/admin_quota' => array()
	);
/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(),
		'down' => array(),
	);

	public $RolesActionMethod = array(
		'up'   => '_allowRolesForAction',
		'down' => '_removeRolesForAction'
	);

	public function __construct($options = array()) {
		parent::__construct($options);
		$this->AclAco = ClassRegistry::init('Acl.AclAco');
	}

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		$success = true;
		$addAco = $this->RolesActionMethod[$direction];
		foreach($this->RolesAction as $action => $roles) {
			try {
				 $this->$addAco($action, $roles);
			} catch (Exception $e) {
				$success = false;
			}
		}
	
		return $success;
	}

	protected function _allowRolesForAction($action, $roles) {
		$actionSplited = explode('/', $action);
		$plugin = $actionSplited[0];
		if (!CakePlugin::loaded($plugin)) {
			CakePlugin::load($plugin);
		}
		return $this->AclAco->addAco($action, $roles);
	}

	protected function _removeRolesForAction($action, $roles) {
		return $this->AclAco->removeAco($action);
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
