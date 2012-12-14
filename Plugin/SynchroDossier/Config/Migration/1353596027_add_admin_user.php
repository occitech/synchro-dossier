<?php
class AddAdminUser extends CakeMigration {

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

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		$userData = array(
			'User' => array(
				'username' => 'admin',
				'role_id' => 1,
				'name' => 'admin',
				'email' => 'admin@occi-tech.com',
				'password' => 'admin31',
				'status' => 1
			)
		);
		ClassRegistry::init('Users.User')->save($userData);
		return true;
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
