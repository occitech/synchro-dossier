<?php
App::uses('ActionsAuthorizer', 'Lib');
class FixUploaderAccessIssues extends CakeMigration {

	public $description = 'fix uploader issues on non (Occitech) admin roles';
	public $actionAuthorizer;

	public function __construct($options = array()) {
		parent::__construct($options);
		$this->actionAuthorizer = new ActionsAuthorizer();
	}

	public $actionRoles = array(
		'Uploader/Files/allFilesUploadedInBatch' => array(
			'sdSuperAdmin','sdAdmin', 'sdUser'
		),
		'Uploader/Files/deleteFile' => array(
			'sdSuperAdmin','sdAdmin'
		),
		'Uploader/Files/find' => array(
			'sdSuperAdmin','sdAdmin', 'sdUser'
		),
		'Uploader/Files/preview' => array(
			'sdSuperAdmin','sdAdmin', 'sdUser'
		),
		'Uploader/Files/deleteFolder' => array(
			'sdSuperAdmin','sdAdmin'
		),
		'Uploader/Files/downloadZipFolder' => array(
			'sdSuperAdmin','sdAdmin', 'sdUser'
		)
	);

	public $RolesActionMethod = array(
		'up'   => 'allowRolesForAction',
		'down' => 'removeRolesForAction'
	);

	public $migration = array(
		'up' => array(
		),
		'down' => array(
		),
	);

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

		foreach ($this->actionRoles as $action => $roles) {
			try {
				 $this->actionAuthorizer->{$addAco}($action, $roles);
			} catch (Exception $e) {
				$success = false;
			}
		}

		return $success;
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
