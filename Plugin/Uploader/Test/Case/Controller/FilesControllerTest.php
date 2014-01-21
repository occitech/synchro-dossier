<?php
App::uses('FilesController', 'Uploader.Controller');
App::uses('CroogoControllerTestCase', 'Croogo.TestSuite');

class FilesControllerTest extends CroogoControllerTestCase {

	public $fixtures = array(
		'plugin.translate.i18n',
		'plugin.croogo.aros_aco',
		'plugin.croogo.aro',
		'plugin.uploader.aco',
		'plugin.sd_users.role',
		'plugin.sd_users.user',
		'plugin.menus.menu',
		'plugin.taxonomy.type',
		'plugin.taxonomy.types_vocabulary',
		'plugin.uploader.vocabulary',
		'plugin.uploader.term',
		'plugin.uploader.taxonomy',
		'plugin.uploader.uploaded_file',
		'plugin.uploader.taxonomies_uploaded_file',
		'plugin.uploader.file_storage',
		'plugin.uploader.comment',
	);

	public function testTagCanBeAddedToAFile() {
		$this->testAction('/uploader/files/addTags/4', array(
			'method' => 'POST',
			'data' => array(
				'Tags' => array(
					'tags' => 'foo, bar, baz, foobar'
				),
			),
		));

		$file = ClassRegistry::init('Uploader.UploadedFile')->find('first', array(
			'conditions' => array('UploadedFile.id' => 4),
			'contain' => array('FileTag', 'FileTag.Term'),
		));

		$foobarFileTagId = ClassRegistry::init('Uploader.FileTag')->getLastInsertId();

		$this->assertEquals(array(1, 2, 3, $foobarFileTagId), Hash::extract($file, 'FileTag.{n}.id'));
	}

}
