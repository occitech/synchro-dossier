<?php
class FixEmptyUsername extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'This migration is irreversible. Down direction will have no effect';

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

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		$success = true;

		if ($direction == 'up') {
			$User = ClassRegistry::init('SdUsers.SdUser');
			$users = $User->find('all', array(
				'conditions' => array(
					'username' => ''
				),
				'contain' => array('Profile'),
				'noRoleChecking' => true
			));
			foreach ($users as &$user) {
				$user['User']['username'] = $this->_generateUsername($user['Profile']);
			}
			if (!empty($users)) {
				$success = $User->saveMany($users);
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

	protected function _generateUsername($profileData) {
		return strtolower(sprintf('%s.%s', $profileData['name'], $profileData['firstname']));
	}
}
