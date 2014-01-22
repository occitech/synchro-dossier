<?php
/**
 * VocabularyFixture
 *
 */
class UploaderVocabularyFixture extends CakeTestFixture {

	public $name = 'Vocabulary';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'alias' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'required' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'multiple' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'tags' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'plugin' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'weight' => array('type' => 'integer', 'null' => true, 'default' => null),
		'updated' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'vocabulary_alias' => array('column' => 'alias', 'unique' => 1)
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
			'title' => 'Vocabulary 1',
			'alias' => 'vocabulary-1',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'required' => 1,
			'multiple' => 1,
			'tags' => 1,
			'plugin' => 'Lorem ipsum dolor sit amet',
			'weight' => 1,
			'updated' => '2014-01-20 09:29:30',
			'created' => '2014-01-20 09:29:30'
		),
		array(
			'id' => 2,
			'title' => 'Vocabulary 2',
			'alias' => 'vocabulary-2',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'required' => 1,
			'multiple' => 1,
			'tags' => 1,
			'plugin' => 'Lorem ipsum dolor sit amet',
			'weight' => 2,
			'updated' => '2014-01-20 09:29:30',
			'created' => '2014-01-20 09:29:30'
		),
		array(
			'id' => 3,
			'title' => 'File Tags',
			'alias' => 'file-tags',
			'description' => null,
			'required' => 0,
			'multiple' => 1,
			'tags' => 0,
			'plugin' => null,
			'weight' => null,
			'updated' => '2014-01-20 09:29:30',
			'created' => '2014-01-20 09:29:30'
		),
	);

}
