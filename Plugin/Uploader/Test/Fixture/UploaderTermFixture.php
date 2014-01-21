<?php
/**
 * TermFixture
 *
 */
class UploaderTermFixture extends CakeTestFixture {

	public $name = 'Term';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'updated' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'slug' => array('column' => 'slug', 'unique' => 1)
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
			'title' => 'foo',
			'slug' => 'foo',
			'description' => null,
			'updated' => '2014-01-20 09:29:40',
			'created' => '2014-01-20 09:29:40'
		),
		array(
			'id' => 2,
			'title' => 'bar',
			'slug' => 'bar',
			'description' => null,
			'updated' => '2014-01-20 09:29:40',
			'created' => '2014-01-20 09:29:40'
		),
		array(
			'id' => 3,
			'title' => 'baz',
			'slug' => 'baz',
			'description' => null,
			'updated' => '2014-01-20 09:29:40',
			'created' => '2014-01-20 09:29:40'
		),
	);

}
