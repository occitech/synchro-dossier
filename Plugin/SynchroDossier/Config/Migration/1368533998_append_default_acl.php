<?php
App::uses('AclLoader', 'Lib');

class AppendDefaultAcl extends CakeMigration {

	public $description = 'add predefined Aco for actions and roles';

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

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		$Loader = new AclLoader('SynchroDossier.acl');
		if ($direction === 'up') {
			$success = $Loader->addAuthorizations();
		} else {
			$success = $Loader->removeAuthorizations();
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
