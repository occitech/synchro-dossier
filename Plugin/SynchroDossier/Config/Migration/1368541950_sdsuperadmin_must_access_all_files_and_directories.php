<?php
class SdSuperAdminMustAccessAllFilesAndDirectories extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
		),
		'down' => array(
		),
	);
	public $AclAco;
	public $permissions;
	public $beforeMethod = array(
		'up' => '_giveAllRightsToSdAdminForAllFiles',
		'down' => '_removeAllRightsToSdAdminForAllFiles'
	);

	public function __construct($options = array()) {
		parent::__construct($options);
		$this->AclAco = ClassRegistry::init('Acl.AclAco');
		$this->permissions = ClassRegistry::init('Acl.AclPermission');
	}

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		$UploadedFileAcoId = $this->_getUploadedFileAcoId();
		$updatePermissions = $this->beforeMethod[$direction];
		return $this->$updatePermissions($UploadedFileAcoId);
	}

	protected function _giveAllRightsToSdAdminForAllFiles($UploadedFileAcoId) {
		$data = array(
			'aro_id' => 4,
			'aco_id' => $UploadedFileAcoId,
			'_create' => 1,
			'_read' => 1,
			'_update' => 1,
			'_delete' => 1,
			'_change_right' => 1
		);
		return $this->permissions->save($data);
	}

	protected function _removeAllRightsToSdAdminForAllFiles($UploadedFileAcoId) {
		return $this->permissions->deleteAll( array(
				'aro_id' => 4,
				'aco_id' => $UploadedFileAcoId,
			)
		);
	}

	protected function _getUploadedFileAcoId() {
		$uploadedFileAco = $this->AclAco->find('first', array(
			'conditions' => array('alias' => 'uploadedFileAco'),
			'fields' => array('id')
		));
		return $uploadedFileAco['Aco']['id'];
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
