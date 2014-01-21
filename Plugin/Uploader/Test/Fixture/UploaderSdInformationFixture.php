<?php
/**
 * SdInformationFixture
 *
 */
class UploaderSdInformationFixture extends CakeTestFixture {

	public $name = 'SdInformationFixture';

/**
 * Table name
 *
 * @var string
 */
	public $table = 'sd_information';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'quota_mb' => array('type' => 'integer', 'null' => false, 'default' => null),
		'current_quota_mb' => array('type' => 'integer', 'null' => false, 'default' => null),
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
			'id' => '1',
			'quota_mb' => '10000',
			'current_quota_mb' => '0'
		),
	);

}
