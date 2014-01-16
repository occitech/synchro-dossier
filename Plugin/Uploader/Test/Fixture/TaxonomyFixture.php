<?php
/**
 * TaxonomyFixture
 *
 */
class TaxonomyFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 20),
		'term_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
		'vocabulary_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'parent_id' => 1,
			'term_id' => 1,
			'vocabulary_id' => 3,
			'lft' => 1,
			'rght' => 1
		),
		array(
			'id' => 2,
			'parent_id' => 2,
			'term_id' => 2,
			'vocabulary_id' => 3,
			'lft' => 2,
			'rght' => 2
		),
		array(
			'id' => 3,
			'parent_id' => 3,
			'term_id' => 3,
			'vocabulary_id' => 3,
			'lft' => 3,
			'rght' => 3
		),
	);

}
