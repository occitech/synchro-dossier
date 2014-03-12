<?php
class AddUsersCollaborations extends CakeMigration {

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
			'create_table' => array(
				'users_collaborations' => array(
					'id' => array('type' =>'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'primary'),
					'user_id' => array('type' => 'integer', 'null' => false, 'false' => null, 'length' => 20),
					'parent_id' => array('type' => 'integer', 'null' => false, 'false' => null, 'length' => 20),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
				),
			),
			'drop_field' => array(
				'users' => array('creator_id',),
			),
		),
		'down' => array(
			'drop_table' => array(
				'users_collaborations',
			),
			'create_field' => array(
				'users' => array('creator_id' => array('type' => 'integer', 'null' => false, 'default' => null)),
			),
		),
	);

	private $__collaborations = array();

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		if($direction == 'up'){
			$SdUser = $this->generateModel('User');
			$Collaboration = $this->generateModel('UsersCollaboration');
			$this->__collaborations = $SdUser->find('list', array(
				'fields' => array('User.id', 'User.creator_id'),
				'conditions' => array('User.creator_id !=' => 0),
			));
		}
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
		$success = true;

		if($direction == 'up'){
			$Collaboration = $this->generateModel('UsersCollaboration');
			foreach ($this->__collaborations as $id => $creatorId) {
				$Collaboration->create();
				$success = $success && $Collaboration->save(array(
					'user_id' => $id,
					'parent_id' => $creatorId
				));
			}
		}
		return $success;
	}
}
